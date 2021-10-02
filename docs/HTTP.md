# HTTP API

To use the HTTP API you need to navigate to the installer URL which is usually at `https://your_website/installer.php` and follow the steps.

## Welcome

In the welcome screen you get a summary of the installation process. If you see this screen it means that the server meets the system requirements.

![welcome](src/welcome.png)

## License

To use the Installer you will require a Chevereto License. In this screen you need to enter your license to continue.

![license](src/license.png)

## Database

Here you need to provide the database credentials for database required for Chevereto.

![database](src/database.png)

## Ready to install

To use Chevereto you must to agree to our EULA so kindly read it.

![ready](src/ready.png)

## Completed

On complete you will get a summary of the process.

![completed](src/completed.png)

## System requirements

If the server lacks the requirements for Chevereto it will throw a message like this:

![requirements](src/requirements.png)

Kindly note that these are **server requirements errors**, you need to tweak/fix your server to meet these requirements.

## Nginx server rules

You can generate Nginx server rules on the fly with HTTP GET.

```text
GET /installer.php?getNginxRules HTTP/1.1
```
