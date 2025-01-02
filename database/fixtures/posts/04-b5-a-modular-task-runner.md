---
title: b5 - a modular task runner
slug: b5-a-modular-task-runner
excerpt: In this article we will take a look into b5 - a modular task runner written in Python by TEAM23.
published_at: 2020-01-26 00:00:00
---

This is the second article of the series Local development with Docker, b5 and traefik.
If you haven’t read the first part you can read about the intentions on why we developed b5.

## Installation

I assume you are using macOS (I use 10.15.2) through the whole series. b5 is written in python and can be installed via
homebrew.

```bash
brew tap team23/b5 https://git.team23.de/build/homebrew-b5.git
brew install b5
```

b5 itself has only python3 and a few libraries as dependencies which will be installed automatically if not present. But
there are of course modules which need other dependencies like the Docker module which needs Docker and Docker Compose
installed. In the Docker Desktop version they are bundled together.

```bash
brew cask install docker
```

## Conventions over configuration

We have a few conventions for software projects in our company. Our default project structure looks like this:

```
- build
	- config.yml
	- Taskfile
- web
	- composer.json
	- vendor
```

As you can see we place the actual application in a `web` folder. This is normally the folder which is deployed. Next to
it is a `build` folder which is for infrastructure related things and the b5 configuration. The application itself does
not know anything about b5 and never should. It is a tool only used during development.
The last thing b5 needs is a local git repository. Later on you can use any b5 command from any folder inside your
project. This works because b5 iterates up folder by folder searching for the `.git` directory. From there it goes to
the configured `run-path` which is `build` by default but can be set via `--run-path` .

## Taskfile

The `Taskfile`  is in general a normal bash script which will be loaded by b5 using the bash `source` command . Because
of this you may use anything you are already using when writing your bash scripts.

For defining tasks you will need to add functions following the `task:name`-schema.
A simple `Taskfile` can look like this:

```bash
#!/usr/bin/env bash
# b5 Taskfile, see https://git.team23.de/build/b5 for details

task:css() {
    sassc input.scss output.css
}

task:composer() {
    (
		  # Use a sub shell to not remain in the web directory
        cd ../web && \
        composer "$@"
    )
}
```

Now you can call `b5 composer {composer-cmd}` from anywhere in your project.

More information about the `Taskfile`  can be found in
the [documentation](https://github.com/team23/b5/blob/master/docs/02_Taskfile_format.md).

## config.yml

The config.yml will be interpreted by b5 on execution and converted to bash variables. The following config file

```yaml
project:
    name: Example Project
        key: example
paths:
    web: ../web
```

is transformed into

```bash
#!/usr/bin/env bash

CONFIG_project_name="Example Project"
CONFIG_project_key="example"
CONFIG_project_KEYS=("name", "key")
CONFIG_paths_web="../web"
CONFIG_paths_KEYS=("web")
```

More information about the `config.yml` can be found in
the [documentation](https://github.com/team23/b5/blob/master/docs/04_config.md).

## The b5 docker module

The b5 docker module provides a convenient wrapper around Docker Compose. First of all we need to enable it inside the
`config.yml` . This is as easy as adding a top level key named `modules` with a sub key named `docker`.

```yaml
project:
    key: example
modules:
    docker:
```

It sets the `COMPOSE_PROJECT_NAME` for any commands called by it to the `project key` defined in the `config.yml` . This
way we can ensure that different projects do not clash with each other because of equal named services in the
docker-compose.yml.

By default b5 looks for a docker-compose.yml file inside of the `build` directory.
To execute via the Taskfile you can define tasks like this:

```bash
#!/usr/bin/env bash
# b5 Taskfile, see https://git.team23.de/build/b5 for details

task:run() {
    docker:docker-compose up "$@"
}

task:halt() {
    docker:docker-compose down "$@"
}

task:docker-compose() {
    docker:docker-compose "$@"
}

```

You can use any `docker-compose` command with `task:docker-compose`  and you have two convenient tasks to start and stop
the project.

Another feature is the option to define commands which can be used in the `Taskfile` . Here an example:

```yaml
project:
    key: example
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
                workdir: /app/web/
            artisan:
                bin: [ "php", "./artisan" ]
                service: php
                workdir: /app/web/
```

These commands can be used inside the Taskfile like this:

```bash
#!/usr/bin/env bash
# b5 Taskfile, see https://git.team23.de/build/b5 for details

task:composer() {
    docker:command:composer "$@"
}

task:artisan() {
    docker:command:artisan "$@"
}

task:phpunit() {
    docker:command:phpunit "$@"
}
```

In the next article of this series we will build an example project with b5, docker-compose and Laravel.

If you have any questions, do not hesitate to write me a [mail](mailto:manuel@christlieb.eu).
