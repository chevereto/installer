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

- PHP 7.4
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
php installer.php -a checkLicense -l LicenseKeyToCheck
```

## API Actions

### checkLicense

Checks Chevereto license (paid edition).

Parameters:

| HTTP    | CLI |
| ------- | --- |
| license | l   |

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

### cPanelProcess

Creates a database, database user and grant privileges.

Parameters (cPanel credentials):

| HTTP     | CLI |
| -------- | --- |
| user     | u   |
| password | x   |

### download

Download the target software.

Parameters (license is optional, needed for software=`chevereto`):

| HTTP     | CLI |
| -------- | --- |
| software | s   |
| license  | l   |

Note: When using CLI pass `l=key`.

- Software: `chevereto`, `chevereto-free`

### extract

Extract the downloaded software file (filePath) in the target working path (absolute paths).

Parameters:

| HTTP        | CLI |
| ----------- | --- |
| software    | s   |
| workingPath | p   |
| filePath    | f   |

- Software: `chevereto`, `chevereto-free`

## createSettings

Generates `app/settings.php` containing the database details.

| HTTP         | CLI |
| ------------ | --- |
| host         | h   |
| port         | p   |
| name         | n   |
| user         | u   |
| userPassword | x   |

## submitInstallForm

Submits the installation form at `/install`.

Parameters:

| HTTP                 | CLI |
| -------------------- | --- |
| username             | u   |
| email                | e   |
| password             | x   |
| email_from_email     | f   |
| email_incoming_email | i   |
| website_mode         | m   |

- website_mode: `community`, `personal`

## selfDestruct

Self-remove the `installer.php` file and `installer.error.log`.

No parameters required.

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

The response is in plain text and looks like this:

```text
# Chevereto nginx generated rules for http://localhost/
## Disable access to sensitive files
location ~* /(app|content|lib)/.*\.(po|php|lock|sql)$ {
  deny all;
}
## CORS headers
location ~* /.*\.(ttf|ttc|otf|eot|woff|woff2|font.css|css|js) {
  add_header Access-Control-Allow-Origin "*";
}
## Upload path for image content only and set 404 replacement
location ^~ /images/ {
  location ~* (jpe?g|png|gif) {
      log_not_found off;
      error_page 404 /content/images/system/default/404.gif;
  }
  return 403;
}
## Pretty URLs
location / {
  index index.php;
  try_files $uri $uri/ /index.php?$query_string;
}
# END Chevereto nginx rules
```
