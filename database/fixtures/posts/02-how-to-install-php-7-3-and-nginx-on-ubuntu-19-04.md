---
title: How to install PHP 7.3 and Nginx on Ubuntu 19.04
slug: how-to-install-php-7-3-and-nginx-on-ubuntu-19-04
excerpt: In this article I will show you how to install Nginx and PHP 7.3 on a blank Ubuntu server.
published_at: 2019-09-26 00:00:00
---

## Add repositories to get the latest versions

First we will add two new repositories to our apt sources list.

```bash
sudo add-apt-repository ppa:ondrej/nginx
sudo add-apt-repository ppa:ondrej/php
```

## Install required packages

In this step we will install all the required packages. When using Nginx it is common, to use PHP's FastCGI process
manager (php-fpm). Maybe you don't need all the php extensions, but this set is pretty common.

```bash
sudo apt install nginx php7.3-fpm php7.3-common php7.3-curl php7.3-intl php7.3-gd php7.3-dev php7.3-json php7.3-mbstring php7.3-mysql php7.3-opcache php7.3-soap php7.3-sqlite3 php7.3-xml php7.3-zip
```

## Configure Nginx to serve a specific domain

First lets have a look at the configuration conventions of Nginx. You can find the default configuration in
`/etc/nginx/sites-available/default` . Additionally it is symlinked to `/etc/nginx/sites-enabled/default` . Through this
symlink the configuration file will be recognized by Nginx at startup and reload. This means configuration changes are
not recognized while runtime.   
Lets add a new configuration file for our domain. The domain name in the file means nothing to Nginx but it is a
convention to name it like the domain it configures.

```bash
sudo touch /etc/nginx/sites-available/christlieb.eu.conf
```

Use you favorite text editor to paste in the following content. Any section is described by the comment above it.

```
# The server directive says Nginx that this is a new server configuration
server {
        # This has to be the domain you want to use
        server_name christlieb.eu;
        # This is the document root
        root /var/www/christlieb.eu/current/public;
        # This is the file which gets loaded by default. index.html gets loaded if there is no index.php
        index index.php index.html;

        # This configuration prevent the logger to log not found favicon
        location = /favicon.ico {
                log_not_found off;
                access_log off;
        }

        # Same as favicon but for robots.txt
        location = /robots.txt {
                allow all;
                log_not_found off;
                access_log off;
        }

        # This says the Nginx server to rewrite any requests which do not access a valid file to rewrite on to the index.php
        location / {
                try_files $uri $uri/ /index.php?$args;
        }

        # This gets all requests from the location above and sends them to the php-fpm socket which will execute the php
        location ~ \.php$ {
                include fastcgi.conf;
                fastcgi_intercept_errors on;
                fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
        }

        # This says that all files with the given endings should be cached by the client
        location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
                expires max;
                log_not_found off;
        }

}
```

```bash
ln -s /etc/nginx/sites-available/christlieb.eu.conf /etc/nginx/sites-enabled/christlieb.eu.conf
```

As said above we need to symlink the file into the sites-available directory.   
To test the configuration execute `sudo nginx -t` . The output should be something like the following.

```
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

Now we can calmly reload the Nginx configuration with `suco service nginx reload`.

## Test the setup

To test the setup we will create a index.php file with a phpinfo() call.

```bash
mkdir -p /var/www/christlieb.eu/current/public
echo "<?php phpinfo();" > /var/www/christlieb.eu/current/public/index.php
```

Now open the browser and point to you your domain, You will see a php info page.
