# Compose

Compose file: [httpd-php.yml](../httpd-php.yml)

## Up

Run this command to spawn (start) Chevereto Installer.

```sh
docker-compose \
    -p chevereto-installer-v4 \
    -f httpd-php.yml \
    up --abort-on-container-exit
```

[localhost:8040/installer.php](http://localhost:8040/installer.php)

## Stop

Run this command to stop Chevereto.

```sh
docker-compose \
    -p chevereto-installer-v4 \
    -f httpd-php.yml \
    stop
```

### Down (uninstall)

Run this command to down Chevereto (stop containers, remove networks and volumes created by it).

```sh
docker-compose \
    -p chevereto-installer-v4 \
    -f httpd-php.yml \
    down --volumes
```

## Logs

Tail `installer.log`.
