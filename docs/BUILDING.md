# Building

## Components

* `./app.php` The actual application (front controller).
* `./src` Contains the PHP sources.
* `./src/build.php` The `build/installer.php` maker.
* `./html` Contains HTML related resources (images, js, css).
* `./template` Contains the templates.

## Build

```sh
php src/build.php
```

```sh
[OK] build/installer.php
```

## Docker build

* **Tip:** Tag `ghcr.io/chevereto/httpd-php:4-installer` to override the [ghcr package](https://github.com/orgs/chevereto/packages?repo_name=installer) with local

```sh
docker build -t ghcr.io/chevereto/httpd-php:4-installer . \
    -f httpd-php.Dockerfile
```

* For custom tag: Replace `tag` with your own.

```sh
docker build -t chevereto/httpd-php:tag . \
    -f httpd-php.Dockerfile
```
