# Installer

> 🔔 [Subscribe](https://newsletter.chevereto.com/subscription?f=PmL892XuTdfErVq763PCycJQrvZ8PYc9JbsVUttqiPV1zXt6DDtf7lhepEStqE8LhGs8922ZYmGT7CYjMH5uSx23pL6Q) to don't miss any update regarding Chevereto.

![Chevereto](LOGO.svg)

[![Community](https://img.shields.io/badge/chv.to-community-blue?style=flat-square)](https://chv.to/community)
[![Discord](https://img.shields.io/discord/759137550312407050?style=flat-square)](https://chv.to/discord)

![welcome](docs/src/welcome.png)

The Installer is a single `.php` file that installs Chevereto. It is an API client which interacts with `chevereto.com` API for providing Chevereto installation.

## Requirements

* Inherits **all** the [Chevereto Requirements](https://v3-docs.chevereto.com/setup/server/requirements.html).

## Features

* HTTP / CLI API
* Checks for system requirements
* Automatic cPanel database setup
* Database checks
* Auto-generated Nginx server rules

## Documentation

* [INSTALLING](docs/INSTALLING.md)
* [HTTP API](docs/HTTP.md)
* [CLI API](docs/CLI.md)

## Building

### Components

* `./app.php` The actual application (front controller).
* `./make.php` The `build/installer.php` single-file maker.
* `./html` Contains HTML related resources (images, js, css).
* `./src` Contains the PHP sources.
* `./template` Contains the templates.

### Build

```sh
php make.php
```

```sh
[OK] build/installer.php
```

## Development

### HTTP server

Spawn PHP development HTTP server.

* To spawn [127.0.0.1:8888/installer.php](http://127.0.0.1:8888/installer.php)

```sh
php -S 127.0.0.1:8888 -t build
```

* To spawn [127.0.0.1:8889/app.php](http://127.0.0.1:8889/app.php) - **Beware:** It will use the project path as working folder!

```sh
php -S 127.0.0.1:8889 -t .
```

### Database

```sh
docker run -p 127.0.0.1:3306:3306 \
    --name chv-installer-db \
    -e MYSQL_ROOT_PASSWORD=password \
    -e MYSQL_DATABASE=chevereto \
    -e MYSQL_USER=chevereto \
    -e MYSQL_PASSWORD=user_database_password \
    -d mariadb:focal
```
