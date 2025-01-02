---
title: b5 example project with Laravel
slug: b5-example-project-with-laravel
excerpt: In this article we will setup a Laravel example project with b5. We will leverage services like Apache, PHP, Node.js, Redis, MySQL, phpMyAdmin and MailHog.
published_at: 2020-01-28 00:00:00
---

This is the third article of the series Local development with Docker, b5 and traefik.
If you havent read the [first](https://christlieb.eu/blog/local-development-environment-with-b5-docker-and-traefik)
ans [second part](https://christlieb.eu/blog/b5-a-modular-task-runner), then you should, You can read about the
intentions why we developed b5 and how it works.

## Let's start

Create a new folder for the project with a `build` folder inside and a `config.yml` and a `Taskfile` . As last step we
will initialise a git repository.

```bash
mkdir example-project && cd example-project
mkdir build
touch build/config.yml
touch build/Taskfile
git init
```

First we define our `project key`  inside of the config.yml:

```yaml
project:
    key: example-project
```

## Create a new Laravel application

We assume the we have neither `php` nor `composer` installed locally.
Lets create a simple `docker-compose.yml` file with a single php service:

```yaml
version: "3.7"

services:

    php:
        image: docker.team23.de/docker/php:7.4
        volumes:
            - ../:/app
```

We use the [TEAM23](https://team23.de) `php` image in version 7.4 . It is based on the official `php:7.4-fpm-buster`
with many php extensions enabled, composer installed, optional xdebug support and
preconfigured [msmtp](https://marlam.de/msmtp/). We mount the whole project (we are in the `build` folder and we make
`../`) into the containers `/app` folder.
Next we need to define the composer command in the `config.yml` to create the application:

```yaml
project:
    key: example-project
modules:
    docker:
        commands:
            composer:
                bin: composer
                service: php
                workdir: /app
```

As you can see we set the `workdir` of the composer command to `/app`. Later we will change this to `/app/web` but first
we need to create the `web` folder with the application.
To execute the command we need to define a task in the Taskfile:

```bash
#!/usr/bin/env bash

task:composer() {
    docker:command:composer "$@"
}
```

Now we can create the Laravel application with the following command:

```
b5 composer create-project laravel/laravel web
```

The execution will take some time and at when it finishes we can see a `web` folder with a fresh Laravel application.

## Add a web server

To use the application we need a web server. So lets define it in the `docker-compose.yml` file:

```yaml
version: "3.7"

services:

    php:
        image: docker.team23.de/docker/php:7.4
        volumes:
            - ../:/app

    web:
        image: docker.team23.de/docker/apache:2.4-php
        environment:
        APACHE_DOCUMENT_ROOT: /app/web/public
            volumes:
                - ../:/app
        ports:
            - 8000:80
```

This is also an image form our company but it is open source. Feel free to fork/copy/use it. The mein reason why we use
it is the configurable document root. We will also add a port mapping from the containers pro 80 to the host system port
8000.

Now we need to start the `docker-compose.yml` . We do this with a b5 task which we have to define in the `Taskfile`:

```bash
#!/usr/bin/env bash

task:run() {
    docker:docker-compose up "$@"
}

task:halt() {
    docker:docker-compose down "$@"
}

task:composer() {
    docker:command:composer "$@"
}
```

Now we can start the project with a simple `b5 run` command.
We should now be able to see Laravels welcome page at `localhost:8000` .

Lets define a command for artisan and phpunit with there respective tasks. Additionally we can update the composer
commands `workdir` to `/app/web` .

config.yml:

```yaml
project:
    key: example-project
modules:
    docker:
        commands:
            composer:
                bin: composer
                service: php
                workdir: /app/web
            phpunit:
                bin: [ "php", "./vendor/bin/phpunit" ]
                service: php
                workdir: /app/web
            artisan:
                bin: [ "php", "./artisan" ]
                service: php
                workdir: /app/web
```

Taskfile:

```bash
#!/usr/bin/env bash

# ...

task:artisan() {
    docker:command:artisan "$@"
}

task:phpunit() {
    docker:command:phpunit "$@"
}
```

Now we are able to execute any artisan command with `b5 artisan {anything}` from anywhere inside the project. The same
with `b5 phpunit` . Pretty cool!

## We need a MySQL server

The apache and php container are running as expected but until now e have no database. Let’s fix this by adding a mysql
service with a volume for persistence to the `docker-compose.yml`  file:

```yaml
version: "3.7"

services:

    # ... 

    mysql:
        image: mysql:5.7
        environment:
            MYSQL_ROOT_PASSWORD: secret
            MYSQL_DATABASE: docker
            MYSQL_USER: docker
            MYSQL_PASSWORD: docker
        volumes:
            - mysql:/var/lib/mysql/

volumes:
    mysql:
```

As you can see we use the official `mysql:5.7` image. The image gives us the option to create a default database with
user and password by setting the correct environment variables. We also define the `root password` which we need for the
next step where we add phpMyAdmin. We also create a volume named `db` which will be mountest to the containers
`/var/lib/mysql` folder. This folder holds all the database information of the MySQL service and persists it.

If the project is still running, execute  `b5 halt`  to stop the project. To let our Laravel application know about the
mysql service we need to edit the `.env` file in the `web` directory:

```

# ...

DB_HOST=mysql
DB_DATABASE=docker
DB_USERNAME=docker
DB_PASSWORD=docker

# ...

```

Lets add the MySQL service as a dependency off the php service in the `docker-compose.yml` file. Then we can execute
`b5 artisan` tasks which interact with the database without starting the project with `b5 run`.

```yaml
version: "3.7"

services:

    php:
        image: docker.team23.de/docker/php:7.4
        volumes:
            - ../:/app
        depends_on:
            - mysql

    # ... 
```

To check if the connection work, we execute `b5 artisan migrate` .

The output should look like this:

```
#Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (0.06 seconds)
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table (0.03 seconds)
Migrating: 2019_08_19_000000_create_failed_jobs_table
Migrated:  2019_08_19_000000_create_failed_jobs_table (0.02 seconds)
Task exited ok
```

This tells us that database connection work. To have a graphical user interface for the project we will add a phpMyAdmin
container. First stop the project with `b5 halt` and open the `docker-compose.yml` file.

```yaml
version: "3.7"

services:

    # ... 

    phpmyadmin:
        image: phpmyadmin/phpmyadmin:latest
            environment:
                PMA_HOST: mysql
                PMA_USER: root
                PMA_PASSWORD: secret
            depends_on:
                - mysql
            ports:
                - 8001:80

volumes:
    mysql:
```

We can configure the phpMyAdmin container via environment variables. We will set the `PMA_HOST` to the host name of the
MySQL service which is simply `mysql`. Next we set the `PMA_USER` to `root` and the `PMA_PASSWORD`  to  `secret` which
we defined in the MySQL service. We set `depends_on`  to `mysql`. This means that this service will first start the
MySQL service. At last we add a mapping form the containers port 80 to the port 8001 of the host system.

To verify that the phpMyAdmin service works, start the project with `b5 run` and navigate your browser to
`localhost:8001`.
…and we see it is working. Cool!

## Lets compile our assets with a Node.js service

Laravel ships with [Laravel Mix](https://laravel.com/docs/6.x/mix) which is a convenient wrapper
around [webpack](https://webpack.js.org/).
First stop the project with `b5 halt`. To use it we need a [Node.js](https://nodejs.org/en/) service to our
`docker-compose.yml` which can execute npm for us:

```yaml
version: "3.7"

services:

    # ... 

    node:
        image: node:lts
        volumes:
            - ../:/app

    # ... 
```

Additionally we will define the docker command in the `config.yml` and the task in the `Taskfile` .

config.yml:

```yaml
project:
    key: example-project
modules:
    docker:
        commands:
            # ...
            npm:
                bin: npm
                service: node
                workdir: /app/web
```

Taskfile:

```bash
#!/usr/bin/env bash

# ...

task:npm() {
    docker:command:npm "$@"
}
```

Now we can execute `b5 npm install`  to instal the defined dependencies form the `package.json`. The output should look
like this:

```
Executing task npm

Creating network "example-project_default" with the default driver
npm notice created a lockfile as package-lock.json. You should commit this file.

added 1035 packages from 485 contributors and audited 17259 packages in 82.852s

31 packages are looking for funding
  run `npm fund` for details

found 0 vulnerabilities

Task exited ok
```

Because we have a generic `b5 npm {anything}` command , we can also execute `b5 npm run dev`. To compile the assets.
Node.js service done. Boom!

## Redis FTW

Redis is a in memory key value store which can be used for queues, cache and sessions. Lets add a reds service to the
`docker-compose.yml` file:

```yaml
version: "3.7"

services:

    # ... 

    redis:
        image: redis

    # ... 
```

Since redis is a in memory store we do not need to persist anything (at least in development) , the definition is only 2
lines.

Now update Laravels `.env` file to use redis:

```

# ...

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# ...

REDIS_HOST=redis

# ...

```

Thats all about using redis inside the project. You can see that this is not as hard as you think. Another service done.
Boom!

## Add MailHog to see Mails sent by Laravel

[MailHog](https://github.com/mailhog/MailHog) is a SMTP testing service written in Go with a web ui to see outgoing
mails.
To use it we have to add it as a service in our `docker-compose.yml` file:

```yaml
version: "3.7"

services:

    # ... 

    mail:
        image: mailhog/mailhog
        ports:
            - 8025:8025

    # ... 
```

To use it from Laravel we need to adjust the settings ind the `.env` file:

```
# ...

MAIL_DRIVER=smtp
MAIL_HOST=mail
MAIL_PORT=1025
# ...

```

Now start the project with `b5 run` and navigate the browser to `localhost:8025`.
We can see the MailHog web ui.
To verify that the mail thing works from the Laravel site we can use `tinker`.

Execute `b5 artisan tinker` and Enter the following code to send a mail:

```
Mail::raw('Test Mail', function($message){$message->from('foo@bar.com')->to('random@mail.com');})
```

Now we can see the mail in the MailHog web ui.

And another service done. Boom!

In the next article we will learn about traefik and how we can use it to custom domains in our development environment.

I hope you enjoyed my tutorial. If you have any questions about it feel free to reach out to me
via [mail](mailto:manuel@christlieb.eu).
