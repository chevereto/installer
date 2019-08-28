# Installer

<img src="https://chevereto.com/src/img/installer/screen-v2.png?20190623" style="max-height: 600px;">

## Description

A single `.php` file that installs Chevereto in a web server.

- Checks for system requirements
- Automatic database setup for cPanel based servers
- Database checks
- Post installation procedures
- JSON based calls
- Auto-generated nginx server rules

## Requirements

- PHP 7
- MySQL 8 (5.6 min) / MariaDB 10
- Apache (with `mod_rewrite`) / Nginx

## How to use it

1. Upload the `installer.php` file to your target `public_html` folder.
2. Open your website and follow the steps.

## API

All functions can be accessed programmatically. The API actions bind from methods defined in the `Controller` class.

| Action            | Parameters (string)                                                               | Description                                                                 |
| ----------------- | --------------------------------------------------------------------------------- | --------------------------------------------------------------------------- |
| checkLicense      | license                                                                           | Checks Chevereto license                                                    |
| checkDatabase     | host, port, name, user, userPassword                                              | Checks database credentials and privileges                                  |
| cPanelProcess     | user, password                                                                    | Creates a database, database user and grant privileges (cPanel credentials) |
| download          | software\*, license                                                               | Download the target software, license needed for software=`chevereto`       |
| extract           | software\*, workingPath, filePath                                                 | Extract the downloaded software file (filePath) in the target working path  |
| createSettings    | host, port, name, user, userPassword                                              | Generates `app/settings.php` containing the database details                |
| submitInstallForm | username, email, password, email_from_email, email_incoming_email, website_mode\* | Submits the installation form at `/install`                                 |
| selfDestruct      |                                                                                   | Self-remove the `installer.php` file                                        |

- \*Software: `chevereto`, `chevereto-free`
- \*website_mode: `community`, `personal`

### Requests

All requests must be made using HTTP POST to the installer file and must include the `action` parameter.

Example:

```text
POST /installer.php HTTP/1.1
Host: localhost
Content-Type: multipart/form-data
```

Parameters:

```js
{
   action: "checkLicense",
   license: "LicenseKeyToCheck"
}
```

### Response

All responses are in JSON format and use HTTP status codes. Example:

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
