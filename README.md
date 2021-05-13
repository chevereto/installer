# Installer

> 🔔 [Subscribe](https://newsletter.chevereto.com/subscription?f=PmL892XuTdfErVq763PCycJQrvZ8PYc9JbsVUttqiPV1zXt6DDtf7lhepEStqE8LhGs8922ZYmGT7CYjMH5uSx23pL6Q) to don't miss any update regarding Chevereto.

![Chevereto](LOGO.svg)

[![Discord](https://img.shields.io/discord/759137550312407050?style=flat-square)](https://chv.to/discord)

<img src="https://chevereto.com/src/img/installer/screen-v2.png?20190623" style="max-height: 600px;">

## Description

A single `.php` file that installs Chevereto using PHP.

- HTTP / CLI API
- Checks for system requirements
- Automatic database setup for cPanel based servers
- Database checks
- Post installation procedures
- JSON based calls
- Auto-generated nginx server rules

## Requirements

- PHP 7.4+
- MariaDB 10
- Apache (with `mod_rewrite`) / Nginx (for HTTP API)

## How to use it

1. Upload the `installer.php` file to your target `public_html` folder.
2. HTTP API: Open your website and follow the steps.
3. CLI API: `php installer.php [options]`

## APIs

All functions can be accessed programmatically. Note that HTTP parameters bind to one-char command options for CLI API.

### HTTP API

```text
POST /installer.php HTTP/1.1
Host: localhost
Content-Type: multipart/form-data
```

```js
{
   action: "checkLicense",
   license: "LicenseKeyToCheck"
}
```

### CLI API

```sh
php installer.php -a <ACTION> <PARAMETERS>
```

## API Actions

### checkLicense

Checks Chevereto license (paid edition).

Parameters:

| HTTP    | CLI |
| ------- | --- |
| license | l   |

```sh
php installer.php -a checkLicense -l=<LICENSE_KEY>
```

### checkDatabase

Checks database credentials and privileges.

Parameters (database credentials):

| HTTP         | CLI |
| ------------ | --- |
| host         | h   |
| port         | p   |
| name         | n   |
| user         | u   |
| userPassword | x   |

```sh
php installer.php -a checkDatabase \
    -h localhost \
    -p 3306 \
    -n db_name \
    -u db_user \
    -x db_password
```

### cPanelProcess

Creates a database, database user and grant privileges.

Parameters (cPanel credentials):

| HTTP     | CLI |
| -------- | --- |
| user     | u   |
| password | x   |

```sh
php installer.php -a cPanelProcess \
    -u user \
    -x password
```

### download

Download the target software.

Parameters (license is optional, needed for software=`chevereto`):

| HTTP     | CLI |
| -------- | --- |
| software | s   |
| license  | l   |
| tag      | t   |

```sh
php installer.php -a download \
    -s chevereto \
    -t=latest \
    -l=<LICENSE_KEY>
```

```sh
php installer.php -a download \
    -s chevereto-free \
    -t="1.3.0"
```

### extract

Extract the downloaded software file (filePath) in the target working path (absolute paths).

Parameters:

| HTTP        | CLI |
| ----------- | --- |
| software    | s   |
| workingPath | p   |
| filePath    | f   |

```sh
php installer.php -a extract \
    -s chevereto \
    -p /var/www/html/ \
    -f <DOWNLOADED_FILENAME>
```

```sh
php installer.php -a extract \
    -s chevereto-free \
    -p /var/www/html/ \
    -f <DOWNLOADED_FILENAME>
```

## createSettings

Generates `app/settings.php` containing the database details.

| HTTP         | CLI |
| ------------ | --- |
| host         | h   |
| port         | p   |
| name         | n   |
| user         | u   |
| userPassword | x   |
| filePath     | f   |

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

Submits the installation form at `<website>/install`.

Parameters:

| HTTP                 | CLI |
| -------------------- | --- |
| website              | w   |
| username             | u   |
| email                | e   |
| password             | x   |
| email_from_email     | f   |
| email_incoming_email | i   |
| website_mode         | m   |

- website_mode: `community`, `personal`

```sh
php installer.php -a submitInstallForm \
    -w http://localhost/ \
    -u user -e user@hostname.loc \
    -x password \
    -f from@hostname.loc \
    -i inbox@hostname.loc \
    -m community
```

### Response

All responses are in JSON format and use HTTP status codes:

```json
{
  "code": 200,
  "message": "Downloaded chevereto-pkg-bbf9ab00.zip (4.4 MB @6.27MB/s.)",
  "data": {
    "download": {
      "fileBasename": "chevereto-pkg-bbf9ab00.zip",
      "filePath": "/var/www/html/chevereto.com/chevereto-pkg-bbf9ab00.zip"
    }
  }
}
```

## Nginx server rules

You can generate Nginx server rules on the fly with an HTTP GET.

```text
GET /installer.php?getNginxRules HTTP/1.1
Host: localhost
```
