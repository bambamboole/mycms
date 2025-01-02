---
title: How to use traefik as a reverse proxy in development
slug: how-to-use-traefik-as-a-reverse-proxy-in-development
excerpt: In this article we will configure dnsmasq and traefik to act as a reverse proxy on our development machine. This way we are able to use custom .test domains in all of our docker projects.
published_at: 2020-02-08 00:00:00
---

This is the fourth part of the series about [local development with b5, docker and traefik](https://christlieb.eu/blog/local-development-environment-with-b5-docker-and-traefik). If you haven’t read the first three parts, you should do that to get the full context of this article.

## How to resolve a custom top level domain to localhost
As a prerequisite, we need to install `dnsmasq` to resolve a custom `tld` to localhost.
Throughout the whole series, I will expect that you are using a macOS (10.15.3 from the this was written)
We use brew to install dnsmasq:

```bash
brew install dnsmasq
```

Additionally, we have to add a `dnsmasq.conf` file with one line of configuration:

```bash
echo 'address=/.test/127.0.0.1' > $(brew --prefix)/etc/dnsmasq.conf
```

This line configures `dnsmasq` to point all requests to domains ending on `.test` from `127.0.0.1` .

### Add a custom macOS resolver
We also need to add a resolver to macOS:
```bash
sudo mkdir -p /etc/resolver
sudo bash -c 'echo "nameserver 127.0.0.1" > /etc/resolver/test'
``` 

### Let `dnsmasq` automatically start on boot
To add dnsmasq to the auto start we use `brew services`:

```bash
sudo brew services start dnsmasq
```

Reboot macOS that the changes take effect.

### Test dnsmasq and the resolver
In order to test this part, we can simply ping a random `.test` domain. It should get an answer from `127.0.0.1`.
```bash
ping foobar.test
PING foobar.test (127.0.0.1): 56 data bytes
64 bytes from 127.0.0.1: icmp_seq=0 ttl=64 time=0.038 ms
```


## Set up the traefik project
In our setup, traefik is a dedicated `b5` project that we will put it into a `.b5` folder in our home directory. I will not go into details on the project structure because this is explained in detail in (this)[] article.
```bash
mkdir ~/.b5/traefik && cd ~/.b5/traefik
git init # b5 need a initialised repository in order to work
```

Project structure:
```
- build
	- config.yml
	- docker-compose.yml
	- Taskfile
	- traefik.toml
```

### config.yml

We use `traefik` as a `project key` and enable the docker module of `b5`.
```yaml
project:
  key: traefik
modules:
  docker:
```

### docker-compose.yml

In this tutorial, we will use version `1.7` of traefik. The latest version at the time that this article was written is `2.1`. I tried for a few hours to figure out a working configuration for the latest version but it was all in vain. As soon as I have a working configuration, I will update this post.

```yaml
version: "3.7"

services:

  traefik:
    image: traefik:1.7
    restart: always
    networks:
      - gateway
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
      - ./traefik.toml:/traefik.toml
    ports:
      - 80:80
      - 443:443
      - 8080:8080

networks:
  gateway:
```

We have a `docker-compose.yml` with a single service named `traefik`. With the `restart: always` configuration, it will automatically start when `docker` starts. The service will be attached to the `gateway` network, which is defined at the end of the file. Traefik needs the docker socket to mount into the service next to the `traefik.toml`, which we create in the next step. We map the hosts port `80` and `443`  to traefik because it will proxy all requests later. Port `8080` is for the traefik dashboard which gives us information about running services etc..

### traefik.toml

```ini
################################################################
# Global configuration
################################################################

# Enable debug mode
#
# Optional
# Default: false
#
debug = true

# Entrypoints to be used by frontends that do not specify any entrypoint.
# Each frontend can specify its own entrypoints.
#
# Optional
# Default: ["http"]
#
#defaultEntryPoints = ["http"]
defaultEntryPoints = ["http", "https"]

# Entrypoints definition
#
# Optional
# Default:
[entryPoints]
    [entryPoints.http]
    address = ":80"
    [entryPoints.https]
    address = ":443"

# Traefik logs
# Enabled by default and log to stdout
#
# Optional
#
[traefikLog]

# Enable access logs
# By default it will write to stdout and produce logs in the textual
# Common Log Format (CLF), extended with additional fields.
#
# Optional
#
[accessLog]

################################################################
# Web configuration backend
################################################################

# Enable web configuration backend
[web]

# Web administration port
#
# Required
#
address = ":8080"

################################################################
# Docker configuration backend
################################################################

# Enable Docker configuration backend
[docker]

# Default domain used.
# Can be overridden by setting the "traefik.domain" label on a container.
#
# Optional
# Default: ""
#
domain = "test"

# Expose containers by default in traefik
#
# Optional
# Default: true
#
exposedbydefault = false

watch = true
```

This file contains the complete configuration for traefik to act as a reverse proxy for all our projects in development. The explanations are right in the file.

### Taskfile
The Taskfile will only contain three commands which are self explanitory:

```bash
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


### Start traefik
Now we can start the project with b5. We will demonise it with `-d` :

```bash
b5 run -d
```

The dashboard should now be accessible on `localhost:8080`.

## Configure our example project to use traefik
In the last article of this series, we built an example project with Laravel, phpMyAdmin and MailHog.

This is the current `docker-compose.yml` from our [example project](https://christlieb.eu/blog/b5-example-project-with-laravel):

```yaml
version: "3.7"

services:

# ...

  web:
    image: docker.team23.de/docker/apache:2.4-php
    environment:
      APACHE_DOCUMENT_ROOT: /app/web/public
    volumes:
      - ../:/app
    ports:
      - 8000:80

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

# ...

  mail:
    image: mailhog/mailhog
    ports:
      - 8025:8025

# ...
```

Because we only need to enable traefik for services which need to be accessible from the host system, I have stripped out all services and volumes which are not relevant.

### Add traefiks network
Since our traefik does not run inside this project but in its own project with its own namespace, we need to connect these docker networks. This can be achieved by the following configuration at the end of the `docker-compose.yml`:

```yaml
# ...

networks:
  default:
  traefik_gateway:
    external: true
```

The `default` network is as its name says, the network which is created by default in any project. When we add a new network, we also have to list the `default` network to be created.  Additionally, we add the `traefik_gateway` network which is the network that we defined in our traefik project and mark it as external.

### Enable traefik for the web and the phpMyAdmin service
To enable traefik for the web service, we have to attach the `traefik_gateway` to it and add two labels:

```yaml
# ...

  web:
    image: docker.team23.de/docker/apache:2.4-php
    environment:
      APACHE_DOCUMENT_ROOT: /app/web/public
    volumes:
      - ../:/app
  # ports:
  #   - 8000:80
	networks:
  	  - default
    - traefik_gateway
	labels:
    - traefik.enable=true
    - traefik.docker.network=traefik_gateway

# ...

  phpmyadmin:
    image: phpmyadmin/phpmyadmin:latest
    environment:
      PMA_HOST: mysql
      PMA_USER: root
      PMA_PASSWORD: secret
    depends_on:
      - mysql
  # ports:
  #   - 8001:80  
    networks:
  	  - default
      - traefik_gateway
	labels:
    - traefik.enable=true
    - traefik.docker.network=traefik_gateway

# ...
```

As you can see, we commented out the port mapping because we don't need this anymore. The configuration for both services is absolutely identical.

### Enable traefik for MailHog
MailHog needs an additional configuration because it does not run on port `80` or `443` by default:

```yaml
# ...

  mail:
    image: mailhog/mailhog
  # ports:
  #   - 8001:80
	networks:
  	  - default
    - traefik_gateway
	labels:
    - traefik.enable=true
	- traefik.port=8025 # additional line
    - traefik.docker.network=traefik_gateway

# ...
```

That's all. Now we can test the setup by executing `b5 run`.
The new `frontends` and `backends` in traefik should now be visible n the [`dahboard`](http://localhost:8080).

The scheme regarding which url will be resolved is as follows:  
`{service}.{project_key}.test`

This means we can access our three services with the following urls:

* [phpmyadmin.example-project.test](http://phpmyadmin.example-project.test/)
* [mail.example-project.test](http://mail.example-project.test/)
* [web.example-project.test](http://web.example-project.test/)


## Recap
We configured `dnsmasq` and created an independent `traefik` service which is able to proxy requests for multiple projects at the same time. Since we do not need the port mappings anymore, we can start multiple projects at the same time without shifting the ports. As last step we configured our example project to use traefik for three different services.

Thanks for reading the article. If you have questions just drop me a line [here](https://christlieb.eu/contact).
