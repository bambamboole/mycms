---
title: "Hosting Laravel with Coolify and GitHub: The Definitive Guide"
slug: hosting-laravel-with-coolify-and-github
excerpt: tbd
published_at: 2019-08-16 00:00:00
---

# Hosting Laravel with Coolify and GitHub: The Definitive Guide

Wanna see your Laravel project soar into production without pulling out your hair? Let’s walk through a deployment
that’s simple, direct, and all-around tidy. We’ll use [Coolify](https://coolify.io/) as our hosting platform and GitHub
for version control.
Ready to rock?

---

## 1. Spin Up a New Laravel Project

Start by creating a brand new Laravel project. If you’re rolling with the latest Composer version, running
`composer create-project laravel/laravel coolify-laravel` in your terminal will instantly set you up with a fresh
Laravel skeleton.  
Feel free to place this project anywhere you like on your local machine. Just make sure you keep track of the folder
location.

---

## 2. Build a Multi-Stage Dockerfile

Next, we’ll make Docker do all the heavy lifting. A multi-stage Dockerfile is the secret sauce here because it keeps our
final image lean while still letting us install everything we need in a single file.   
First we have the `cli` container which gets [supercronic](https://github.com/aptible/supercronic) for cron management
installed.
Next comes an intermediate step to build the frontend assets.
Finally, we have the `web` container that will run our Laravel app for web requests.
Below is the actual [christlieb.eu](https://christlieb.eu) Dockerfile that
uses [serversideup’s Docker images](https://serversideup.net/open-source/docker-php/), which already have a bunch of
fancy optimizations built in.

```dockerfile
# Build a cli based container for cron and queue
FROM serversideup/php:8.3-cli as cli

# Switch to root so we can do root things
USER root

# We use the supercronic to run Laravels schedule:run command
# Latest releases available at https://github.com/aptible/supercronic/releases
ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.2.33/supercronic-linux-arm64 \
    SUPERCRONIC_SHA1SUM=e0f0c06ebc5627e43b25475711e694450489ab00 \
    SUPERCRONIC=supercronic-linux-arm64

RUN curl -fsSLO "$SUPERCRONIC_URL" \
 && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
 && chmod +x "$SUPERCRONIC" \
 && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
 && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic \
 # We have to install exif and intl extensions
 && install-php-extensions intl exif \
 # Nano and vim is for convenience ;)
 && apt-get update && apt-get install -y \
        nano vim
# Drop back to our unprivileged user
USER www-data

# Copy the current directory to the container
COPY --chown=www-data:www-data . /var/www/html
WORKDIR /var/www/html

ENV LOG_CHANNEL=stderr
RUN composer install --no-interaction --optimize-autoloader --prefer-dist

# We need an intermediate container to build the frontend assets
FROM node:lts as frontend-build
COPY . /app
RUN cd /app && npm ci && npm run build

# Build the final web container copy over assets and dependencies
FROM serversideup/php:8.3-fpm-nginx as web
ENV LOG_CHANNEL=stderr

# Switch to root so we can do root things
USER root

# here we have to install them again since we are using a different image
RUN install-php-extensions intl exif \
 && apt-get update && apt-get install -y \
        nano vim
# Drop back to our unprivileged user
USER www-data
# Copy the current directory to the container
COPY --chown=www-data:www-data . /var/www/html
COPY --chown=www-data:www-data --from=cli /var/www/html/vendor /var/www/html/vendor
COPY --chown=www-data:www-data --from=frontend-build /app/public/build /var/www/html/public/build
WORKDIR /var/www/html
```

You can tailor this Dockerfile to your needs, but if you’re just starting, stick with this version to keep things
simple.

---

## 3. Define Services with `compose.yml`

Now let’s bring up multiple services with Docker Compose. We’ll have one service for the web server (PHP-FPM and Nginx
combined), one for running our cron jobs, and another for Horizon. [Horizon](https://laravel.com/docs/horizon) is
Laravel’s queue management system, so
if you’re handling queued jobs, this is essential.

```yaml
services:
    web:
        build:
            context: .
            target: web
        ports:
            - "80:80"

    cron:
        build:
            context: .
            target: cli
        command: [ "/usr/local/bin/supercronic", "cron_file" ]
        depends_on:
            - web

    horizon:
        build:
            context: .
            target: cli
        command: [ "php", "/var/www/html/artisan", "horizon" ]
        depends_on:
            - web
        healthcheck:
            test: [ "CMD", "healthcheck-horizon" ]
            start_period: 10s
```

While each service shares the same codebase, they perform different tasks. The `cron` service will handle your Laravel
scheduled tasks, and the `horizon` service will manage and monitor your queued jobs.

*<<[IMAGE: Possibly show a diagram of how these containers interact.]*

---

## 4. Push It All to GitHub

Having everything on your local machine is fine, but [Coolify](https://coolify.io/) needs access to your code. Let’s
push our project to
GitHub:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/coolify-laravel.git
git push -u origin main
```

You might have a naming convention you prefer for branches or commit messages, but this is the general idea. Once it’s
on GitHub, we’re ready to connect it to [Coolify](https://coolify.io/).

*<<[IMAGE: Possibly show a screenshot of a GitHub repo page with the code.]*

---

## 5. Create a New Project in Coolify

Log in to your Coolify dashboard and create a new project. Give it a fancy name—maybe something that’ll show up well in
your Slack brag channels. Once created, you’ll see your brand-new, empty project just waiting for some Docker love.

---

## 6. Add a MySQL Database Resource

A Laravel app without a database is just sad, so let’s give it one. In Coolify, you can go to **Resources**, select *
*Databases**, and then click on **Add New Resource**. Pick MySQL, configure your database, and note the credentials.
Those credentials are going to be your lifeline when hooking the environment variables up.

---

## 7. Create a GitHub App Resource

Next, we jump into **Applications** within your new Coolify project. Click **Add New Resource** and choose **GitHub App
**. Follow the prompts to connect it to your Laravel repository. If everything goes smoothly, Coolify should have full
permission to pull your code from GitHub any time you want to deploy.

---

## 8. Configure Docker Compose

With the GitHub App resource connected, you should specify that you’ll be using Docker Compose. Point Coolify directly
to the `compose.yml` file we wrote earlier. This tells Coolify exactly how to build and run your web, cron, and horizon
services.

---

## 9. Set Your Environment Variables

In the Coolify interface, add the environment variables your Laravel app needs, such as `APP_KEY`, `APP_ENV=production`,
and your database host, username, and password. Make sure these match the credentials you set up in the Coolify MySQL
resource, or you’ll be left wondering why your app can’t connect.

---

## 10. Deploy and Bask in the Glory

Once your environment variables are ready and your config looks good, hit **Deploy**. Coolify will start pulling your
code from GitHub, building the images according to the Dockerfile, and spinning up those containers. If all goes well,
you’ll soon see your app running live and happily connected to the database.

And that’s it. You’re now deploying Laravel with a slick Docker setup on [Coolify](https://coolify.io/), tied neatly to
your GitHub repo. If
you run into any quirks or have questions about a specific step, just let me know. I’m all ears—and somewhat snark.
