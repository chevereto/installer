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

### cPanelProcess

Creates a database, database user and grant privileges. This is only available for cPanel based servers.

`-a cPanelProcess`

| Parameter       | Option |
| --------------- | ------ |
| cPanel User     | u      |
| cPanel Password | x      |

```sh
php installer.php -a cPanelProcess \
    -u user \
    -x password
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
| Software  | s       |
| Tag       | t       |
| License   | l       |

```sh
php installer.php -a download \
    -s chevereto \
    -t=latest \
    -l=LICENSE_KEY
```

You can check the license with the [checkLicense](#checkLicense) action.

### extract

Extract the downloaded software file (filePath) in the target working path (absolute paths).

`-a extract`

| Parameter   | Option |
| ----------- | ------ |
| Software    | s      |
| WorkingPath | p      |
| FilePath    | f      |

```sh
php installer.php -a extract \
    -s chevereto \
    -p /var/www/html/ \
    -f DOWNLOADED_FILENAME
```

## createSettings

Generates `app/settings.php` containing the database details.

`-a createSettings`

| Parameter              | Option |
| ---------------------- | ------ |
| Database Host          | h      |
| Database Port          | p      |
| Database Name          | n      |
| Database User          | u      |
| Database User Password | x      |
| Filepath               | f      |

```sh
php installer.php -a createSettings \
    -h localhost \
    -p 3306 \
    -n db_name \
    -u db_user \
    -x db_password \
    -f app/settings.php
```

## submitInstallForm

Forwards the installation command to the Chevereto CLI shell command. This install the Chevereto database and its admin user.

`-a submitInstallForm`

| Parameter      | Option |
| -------------- | ------ |
| Admin Username | u      |
| Admin Email    | e      |
| Admin Password | x      |

```sh
php installer.php -a submitInstallForm \
    -u user \
    -e user@hostname.loc \
    -x password \
```
