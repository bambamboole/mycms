---
title: Local Development Environment with b5, Docker, and Traefik
slug: local-development-environment-with-b5-docker-and-traefik
excerpt: Learn how we use Docker, Docker Compose and traefik with our self developed task runner b5.
published_at: 2020-01-26 00:00:00
---

For a long time I used to use [Laravel Valet](https://laravel.com/docs/6.x/valet)  as a local development environment.
It was fine until we started to build more complex projects in different programming languages
at  [TEAM23](https://team23.de). We needed a solution flexible enough to manage different stacks in a convenient way.
Instead of predefining an environment on all MacBooks, the development environment should be defined by the project. The
dependencies should also be easy and fast to install for fast onboarding of new developers.
We developed b5. A modular task runner which wraps host system commands in a convenient way. It also helps us to provide
company wide conventions into more than 100 different projects based on PHP, Python and/or Node.js.
As the whole company uses MacBooks, this guide is only tested on macOS (10.15.2 as time of writing).

## This will be a series of articles

To provide detailed instructions on the single topics I have decided to split the full setup into different articles.

1. Docker and Docker Compose (this article)
2. [b5 - a modular task runner](https://christlieb.eu/blog/b5-a-modular-task-runner)
3. [Example project with Laravel](https://christlieb.eu/blog/b5-example-project-with-laravel)
4. [How to use traefik as a reverse proxy in development](https://christlieb.eu/blog/how-to-use-traefik-as-a-reverse-proxy-in-development)

## TL;DR

At the end of the series we will have set up a local development environment based on Docker (especially docker-compose)
where any service (which is basically a running docker container) of any project is reachable by its custom domain.

## What is docker?

[Docker](https://www.docker.com/) is a software to run a single process inside of an encapsulated environment called
container. You can build these containers by yourself and share them via an open source registry
like [Docker Hub](https://hub.docker.com/) which is used by default. This means that you can run a software on your
system without installing it because it will be shipped as an image from Docker Hub and runs encapsulated on your host
system.
Here an example where we run [Composer](https://getcomposer.org/) in the current directory:

``` bash
docker run -it -v $(pwd):$(pwd) -w $(pwd) composer:latest composer install
```

The Flag  `-it`  makes an interactive shell. With `-v`  we can mount directories into the container and `-w` sets the
working directory. With [pwd](https://de.wikipedia.org/wiki/Pwd_(Unix)) we get the current directory. `composer:latest`
tells which [image](https://hub.docker.com/_/composer) should be used. And at the end is the actual command which should
be executed in the container.
output:

```
Composer could not find a composer.json file in /Users/bambamboole
To initialize a project, please create a composer.json file as described in the https://getcomposer.org/ "Getting Started" section
```

We see, the composer.json file is missing. But this means that we have executed composer without installing PHP or
composer locally. Pretty cool.

## What is Docker Compose

With Docker Compose it is possible to run multi container environments inside of a virtual network. This environment is
defined by a `docker-compose.yml` file.
Let’s take a look at a simple example.
Create a new directory and change to it.

```
mkdir docker-compose-example && cd docker-compose-example
```

Create a PHP file in the current directory:

```
echo “<?php phpinfo();” >> index.php
```

Now create a `docker-compose.yml` file with the following content:

```yaml
version: "3.7" # compose file format version

services:

    web:
        image: docker.team23.de/docker/apache:2.4-php
        environment:
            APACHE_DOCUMENT_ROOT: /app
        volumes:
            - ./:/app
        ports:
            - 8000:80

    php:
        image: php:7.4-fpm
        volumes:
            - ./:/app
```

In this example above we built a simple setup which contains both an apache and php-fpm service. The apache image is not
from Docker Hub but from the company where I work [TEAM23](https://www.team23.de/) . But it is also open source. The
image is not special and can be simply built by yourself. It provides an environment variable to configure the document
root which is the main reason why I use the self made image in this example. The second container is the official 7.4
`php-fpm` container. We mount the current directory into the containers `/app` directory. The apache container has a
port mapping which will map the containers port 80 to port 8000 on the host system.

To run the example execute the following command:

```
docker-compose up
```

Now we should see a `phpinfo()` page on `localhost:8000` . This was pretty easy, don’t you think?

To stop the project execute the following command:

```
docker-compose down
```

Later on in this series we will build bigger environments with services such
as [MySQL](https://de.wikipedia.org/wiki/MySQL), [phpMyAdmin](https://www.phpmyadmin.net/), [Redis](https://redis.io/) , [Node.js](https://nodejs.org/en/)
and [Mailhog](https://github.com/mailhog/MailHog) .

In the [next article](https://christlieb.eu/blog/b5-a-modular-task-runner) of this series we will learn
about [b5](https://github.com/team23/b5).

If you have any questions, do not hesitate to write me a [mail](mailto:manuel@christlieb.eu).
