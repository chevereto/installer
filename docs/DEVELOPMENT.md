# Development

## Quick start

* Clone [chevereto/installer](https://github.com/chevereto/installer)
  * Use `4.X` branch `git switch 4.X`
  * Run [docker-compose up](#up)
* [Sync code](#sync-code) to sync changes

## Reference

* `SOURCE` is the absolute path to the cloned chevereto project
* You need to replace `SOURCE=~/git/chevereto/installer` with your own path
* `SOURCE` will be mounted at `/var/www/source/` inside the container
* Chevereto will be available at [localhost:8140/installer.php](http://localhost:8140/installer.php)

✨ This dev setup mounts `SOURCE` to provide the application files to the container. We provide a sync system that copies these files on-the-fly to the actual application runner for better isolation.

## docker-compose

Compose file: [httpd-php-dev.yml](../httpd-php-dev.yml)

Alter `SOURCE` in the commands below to reflect your project path.

## Up

Run this command to spawn (start) Chevereto Installer.

```sh
SOURCE=~/git/chevereto/installer \
docker-compose \
    -p chevereto-installer-v4-dev \
    -f httpd-php-dev.yml \
    up -d
```

## Stop

Run this command to stop Chevereto Installer.

```sh
SOURCE=~/git/chevereto/installer \
docker-compose \
    -p chevereto-installer-v4-dev \
    -f httpd-php-dev.yml \
    stop
```

## Down (uninstall)

Run this command to down Chevereto (stop containers, remove networks and volumes created by it).

```sh
SOURCE=~/git/chevereto/installer \
docker-compose \
    -p chevereto-installer-v4-dev \
    -f httpd-php-dev.yml \
    down --volumes
```

## Sync code

Run this command to sync the application code with your working project.

```sh
docker exec -it \
    chevereto-installer-v4-dev \
    bash /var/www/sync.sh
```

This system will observe for changes in your working project filesystem and it will automatically sync the files inside the container.

**Note:** This command must keep running to provide the sync functionality. You should close it once you stop working with the source.

## Logs

Tail `installer.log`.
