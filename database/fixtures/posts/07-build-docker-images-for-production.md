---
title: Build Docker images for production
slug: build-docker-images-for-production
excerpt: a brief introduction how we handle Docker images for production and how this fundamentally differs to our development images. Our example will be a simple Laravel application.
published_at: 2020-02-10 00:00:00
---

Many people have reached out to me with questions about how I would use the images
from [this setup](https://christlieb.eu/blog/local-development-environment-with-b5-docker-and-traefik) in production.
After the third question, I thought it would be the best to write a small article about building docker images for
production.

## The Dockerfile

As a convention, we put the Dockerfile which will be used in production, in the root of the project.

## Project structure

We have a conventional project structure (read more) which looks like this:

```
- build
	- # files needed for production build
- web
	- # the actual Laravel application
- Dockerfile
```

In my opinion, it is a good pattern to nest the actual application in a sub folder of the GIT repository because there
are many files which are used for infrastructure related stuff, and the application should not be aware of these files.

## Production images != development images

The main difference between the production and the development image is that the production image contains the actual
application code. The development image is a image where we will mount the application code into.
Another difference, at least at my company, is that the development image is a more generic image. It will be used for
any PHP project in development and has optional xdebug, preconfigured msmtp, many enabled php extensions and installed
composer for example.

## The actual Dockerfile

We are leveraging Docker multi stage builds to get our dependencies installed and built.

### PHP dependencies

```dockerfile
FROM composer:latest as step1
COPY ./web ./app/web
WORKDIR /app/web
RUN composer install —-no-dev —-no-scripts —-optimize-autoloader

# ... 
```

In `step1`, we will use the `composer:latest` image. First, we have to copy our actual application into the container.
Next, we set the current `WORKDIR` to
`/app/web` . Now we can run composer install with a few options.

### Frontend dependencies

```dockerfile
# ... 

FROM node:lts as step2
COPY --from=step1 /app/web /app/web
WORKDIR /app/web
RUN npm install && npm run prod && rm -rf node_modules

# ... 
```

For `step2`, we will use the `node:lts` image to install and build our frontend dependencies. The next step is to copy
the files form the first stage to the second stage. Unfortunately, we have to set the `WORKDIR` in every stage. To
install and build the frontend dependencies and to delete the not used `node_modules`, we execute
`npm install && npm run prod && rm -rf node_modules`.

Now we have installed and built all our dependencies.

### Webserver configuration

As last stage we use the official `php:7.4-apache` image.

```dockerfile
FROM php:7.4-apache
COPY --from=step2 /app /app
COPY build/vhost.conf /etc/apache2/sites-available/000-default.conf

# ...

```

We copy the files with all dependencies installed and built from `step2` to its final position. Next, we copy a
`vhost.conf` file from our `build` folder into the containers `/etc/apache2/sites-available/` folder with the name
`000-default.conf`. This file is the configuration file which is loaded by apache by default. It looks like this:

```dockerfile
<VirtualHost *:80>
  DocumentRoot /app/web/public

  <Directory "/app/web/public">
    AllowOverride all
    Require all granted
  </Directory>

  ErrorLog ${APACHE_LOG_DIR}/error.log
  CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Nothing special is going on here. We just need this file to point the web server to the correct document root
`/app/web/public`.

### PHP extensions

We need to install a few PHP extensions for the runtime.

```dockerfile
# ...

# Install needed php extenstions
RUN docker-php-ext-install -j “$(nproc)” \
        bcmath \
        opcache \
        pdo_mysql

# ...
```

### Final configuration

As a final configuration, we change the owner of `/app` to the web server user `www-data` and activate the apache module
`rewrite` which is needed to enable `.htaccess` configuration.

```dockerfile
RUN chown -R www-data:www-data /app \
    && a2enmod rewrite

WORKDIR /app/web
```

Only for convenience do we set the `WORKDIR` to the directory of our application. When we execute a command via Docker
in the container we do not have to set the `WORKDIR` explicitly.

## The full Dockerfile

The result at the end should look like this:

```dockerfile
FROM composer:latest as step1
COPY ./web /app/web
WORKDIR /app/web
RUN composer install --quiet --optimize-autoloader —-no-dev

FROM node:lts as step2
COPY --from=step1 /app /app
WORKDIR /app/web
RUN npm install --no-optional && npm run prod && rm -rf node_modules

FROM php:7.4-apache
COPY --from=step2 /app /app
COPY build/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install needed php extenstions
RUN docker-php-ext-install -j "$(nproc)" \
        bcmath \
        opcache \
        pdo_mysql

RUN chown -R www-data:www-data /app \
    && a2enmod rewrite

WORKDIR /app/web
```

To build and start the image, we need to execute the following commands:

```bash
docker build -t docker-example .
docker run -p 8000:80 -e APP_KEY=base64:w0T2so9vxBfHWm5q0jQuJHhtQwHnWGdqRsXf2S7KtcE= docker-example:latest
```

We build the image and add a tag with `-t`. In our case, `docker-example`.
To execute the image, we use the `docker run` command. With `-p 8000:80`, we map the containers port `80` to the port
`8000` on our host system. As you can see, we add an `APP_KEY` as environment variable. Without this, we would get a
`500` server error.
Now we can open the browser and point it to `localhost:8000`. Boom, we see the Laravel welcome page.

Thanks for reading my article. If you have questions just drop me a line [here](https://christlieb.eu/contact).
