# CLI API

To use the CLI API you need to run commands in the form:

```sh
php installer.php -a ACTION \
    <PARAMETERS>
```

## Request

The following reference indicates which parameters you need to pass for each available action.

### checkLicense

Checks Chevereto license status. Note that a valid license will be required at [download step](#download).

`-a checkLicense`

| Parameter | Option |
| --------- | ------ |
| license   | l      |

```sh
php installer.php -a checkLicense -l=LICENSE_KEY
```

### checkDatabase

Checks database credentials and privileges. Note that an empty database is required.

`-a checkDatabase`

| Parameter    | Option |
| ------------ | ------ |
| host         | h      |
| port         | p      |
| name         | n      |
| user         | u      |
| userPassword | x      |

```sh
php installer.php -a checkDatabase \
    -h localhost \
    -p 3306 \
    -n db_name \
    -u db_user \
    -x db_password
```

### download

Download the target Chevereto software. Note that the license must be active to download Chevereto.

`-a download`

| Parameter | Options |
| --------- | ------- |
| Tag       | t       |
| License   | l       |

```sh
php installer.php -a download \
    -t=4 \
    -l=LICENSE_KEY
```

You can check the license with the [checkLicense](#checkLicense) action.

### extract

Extract the downloaded software file (-f) in the target path (-p).

`-a extract`

| Parameter   | Option |
| ----------- | ------ |
| WorkingPath | p      |
| FilePath    | f      |

```sh
php installer.php -a extract \
    -p /var/www/html/ \
    -f chevereto-pkg-*.zip
```

**Note**: The installer will backup the extracted `index.php` Chevereto front-controller as `index.chevereto.php` in the target working path (-p). This can be restored by running [selfDestruct](#selfdestruct) action.

## createEnv

Generates the `.env` file containing the database environment variables.

`-a createEnv`

| Parameter                  | Option |
| -------------------------- | ------ |
| DB Host                    | h      |
| DB Port                    | p      |
| DB Name                    | n      |
| DB User                    | u      |
| DB User Password           | x      |
| Filepath for the .env file | f      |

```sh
php installer.php -a createEnv \
    -h localhost \
    -p 3306 \
    -n db_name \
    -u db_user \
    -x db_password \
    -f .env
```

## lock

Creates a `installer.lock` file to prevent using the installer.

```sh
php installer.php -a lock
```

## selfDestruct

Destructs the installer file (-f) and restore the `index.chevereto.php` file as `index.php` front controller in the working path (-p).

`-a selfDestruct`

| Parameter   | Option |
| ----------- | ------ |
| WorkingPath | p      |
| FilePath    | f      |

```sh
php installer.php -a selfDestruct \
    -p /var/www/html/ \
    -f /var/www/html/installer.php
```
