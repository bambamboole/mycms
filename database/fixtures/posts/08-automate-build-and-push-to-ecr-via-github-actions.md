---
title: Automate build and push to ECR via GitHub Actions
slug: automate-build-and-push-to-ecr-via-github-actions
excerpt: In this article we will build a minimal PHP application consisting of two containers which are declared in a single multi stage Dockerfile. Then we will leverage a GitHub Actions workflow to automate building the Docker images and pushing them to ECR
published_at: 2022-05-06 00:00:00
---

Let's build a simple containerised PHP application and a GitHub Actions workflow
to build and push the images to
ECR, which is the container registry from AWS.

## Prerequisites

* an IAM credentials with permissions to create and push to ECR repositories
* GitHub repository with the IAM credentials declared as secrets
* Configured `awscli` with the account to create the ECR repository

## The application

For the sake of this demo we keep the php application minimal and reduce it to
the following tree.

```
│ composer.json
└─public
│ │ index.php
└─src
│ │ Application.php
```

The `composer.json` file has the following content:

```json
{
    "autoload": {
        "psr-4": {
            "Acme\\Service\\": "src/"
        }
    }
}
```

As said, we keep it minimal. This `composer.json` does only include one
autoloader definition.

Here is the `src/Application.php`:

```php
<?php

namespace Acme\Service;

class Application
{
    public function run(): string
    {
        return 'it works!';
    }
}
```

and here the `public/index.php`:

```php
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = new \Acme\Service\Application();
echo $app->run();

exit(0);
```

That's the application. As stated in the beginning, we keep it minimal.
Nevertheless, extending afterwards will be a no-brainer.

## Dockerfile

Let's focus next on the Dockerfile. It will contain a multiple stages. We will
build two distinct images out of it. One for `php-fpm` and the other for `nginx`.  
Here is the content of the `Dockerfile`:

```
# The first stage named `base` contains the needed/wanted
# steps to execute our application in production
FROM php:8.1-fpm-alpine as base

WORKDIR /var/www/html

RUN apk --no-cache add bash nano

# The second stage named `develop` contains the software which is needed to execute
# our application in development as well as build out software for production
FROM base as develop

RUN apk add --no-cache git \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && mkdir -p /.composer /.config \
 && chmod -R 2777 /.composer /.config

# The third stage named `build` will execute the build steps needed to execute
# our application in production. Currently only `composer install`
FROM develop as build

COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev --prefer-dist

# The fourth stage named `production` inherits from our `base` stage
# and copies the built application files from the `build` stage
FROM base as production

COPY --from=build /var/www /var/www

# The fifth stage named `nginx` is our custom nginx image, since nginx needs the content
# of our document root. Currently only the `index.php` which is needed to proxy the
# request to our `php-fpm` container, but could potentially contain any kind
# of assets like CSS, JavaScript or images, which we need to serve
FROM nginxinc/nginx-unprivileged:1.20-alpine as nginx

COPY --from=build /var/www/html/public /var/www/html/public
```

I have added comments to all stages which describe what's happening there.

## ECR repository

To create a new ECR repository with the `awscli`, you have to execute the
following command:

```bash
aws ecr create-repository --repository-name acme-service
```

## GitHub Action

Let's first commit and push everything into the `main` branch of our GitHub
repository
before we start with our GitHub Action workflow.

Next we have to declare

To define a workflow we need to create a YAML file in `.github/workflows` in our
repository:

```bash
mkdir -p .github/workflows
touch .github/workflows/on-push-main-branch.yml
```

Here is the complete content of the workflow:

```yaml
name: Build and push docker images

on:
    push:
        branches:
            - main

jobs:
    build-and-deploy:
        runs-on: ubuntu-latest
        steps:
            -   name: Checkout
                uses: actions/checkout@v2
            -   name: Configure AWS credentials
                uses: aws-actions/configure-aws-credentials@v1
                with:
                    aws-access-key-id: ${{ secrets.AWS_DEPLOY_ACCESS_KEY_ID }}
                    aws-secret-access-key: ${{ secrets.AWS_DEPLOY_SECRET_ACCESS_KEY }}
                    aws-region: eu-central-1
            -   name: Login to Amazon ECR
                uses: aws-actions/amazon-ecr-login@v1
                id: login-ecr
            -   name: Build & Push Images
                run: |
                    CURRENT_SHA=${GITHUB_SHA::8}
                    REPOSITORY=${{ steps.login-ecr.outputs.registry }}/acme-service

                    docker build . --target production -t ${REPOSITORY}:latest -t ${REPOSITORY}:${CURRENT_SHA}
                    docker build . --target nginx -t ${REPOSITORY}:nginx-latest -t ${REPOSITORY}:nginx-${CURRENT_SHA}

                    docker push --all-tags ${REPOSITORY}
```

We will go through the `on-push-main-branch.yml` step by step:

First comes the `name` and `trigger` declaration:

```yaml
name: Build latest tag and deploy to staging

on:
    push:
        branches:
            - main
# ...
```

This workflow will only be executed by a push to the `main` branch.

Next we have the `jobs` declaration:

```yaml
# ...
jobs:
    build-and-deploy:
        runs-on: ubuntu-latest
        steps:
# ...
```

Our workflow contains only one job named `build-and-deploy`. It will run
on `ubuntu-latest`.

The `build-and-deploy` job contains multiple steps.

### Step 1: Checkout code

```yaml
    -   name: Checkout
        uses: actions/checkout@v2
```

This step will checkout the coe of the repository and makes it available in the
working directory

### Step 2: Configure AWS credentials

```yaml
  -   name: Configure AWS credentials
      uses: aws-actions/configure-aws-credentials@v1
      with:
      aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
      aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
      aws-region: eu-central-1
```

Here we configure the access to AWS via the added secret from the prerequisites.
You may adapt the region to your needs.

### Step 3: Login to our ECR registry

```yaml
  -   name: Login to Amazon ECR
      uses: aws-actions/amazon-ecr-login@v1
      id: login-ecr
```

This actions as well as the one before are provided by AWS and make our life really easy.
We can access the registry url in the next step via the `id`.

### Step 4: Build and push Docker images

This is the last step in our workflow which will finally build and push the needed docker images.

```yaml
  -   name: Build & Push Images
      run: |
          CURRENT_SHA=${GITHUB_SHA::8}
          REPOSITORY=${{ steps.login-ecr.outputs.registry }}/acme-service

          docker build . --target production -t ${REPOSITORY}:latest -t ${REPOSITORY}:${CURRENT_SHA}
          docker build . --target nginx -t ${REPOSITORY}:nginx-latest -t ${REPOSITORY}:nginx-${CURRENT_SHA}

          docker push --all-tags ${REPOSITORY}
```

First we declare two variables which helps us to keep the following commands short.
`CURRENT_SHA` contains trimmed current commit sha and `REPOSITORY` contains the registry
url (from the step before) suffixed by `/acme-service` which is the absolute url to our ECR repository.  
Now we build our docker images with the right target from our multi-stage Dockerfile and add two tags.
The first tag is the `latest` tag and the second tag is the unique `CURRENT_SHA`.
For our nginx image we prepend the tags with `nginx-` to be able to distinguish between both images.
Finally, we push all local tags for the repository.

## Summary

We build a small PHP application consisting of two docker containers built out of a single multi-stage Dockerfile
and configured a GitHub Actions workflow to build and push the images automatically on every push to our main branch.

Thanks for reading. I hope you can use this small tutorial as a starting point for your container journey!
