# Development

## HTTP server

Spawn PHP development HTTP server.

* To spawn [127.0.0.1:8888/installer.php](http://127.0.0.1:8888/installer.php)

```sh
php -S 127.0.0.1:8888 -t build
```

* To spawn [127.0.0.1:8889/app.php](http://127.0.0.1:8889/app.php) - **Beware:** It will use the project path as working folder!

```sh
php -S 127.0.0.1:8889 -t .
```

## Database

```sh
docker run -p 127.0.0.1:3306:3306 \
    --name chv-installer-db \
    -e MYSQL_ROOT_PASSWORD=password \
    -e MYSQL_DATABASE=chevereto \
    -e MYSQL_USER=chevereto \
    -e MYSQL_PASSWORD=user_database_password \
    -d mariadb:focal
```
