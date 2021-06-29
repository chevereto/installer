# Installer

> 🔔 [Subscribe](https://newsletter.chevereto.com/subscription?f=PmL892XuTdfErVq763PCycJQrvZ8PYc9JbsVUttqiPV1zXt6DDtf7lhepEStqE8LhGs8922ZYmGT7CYjMH5uSx23pL6Q) to don't miss any update regarding Chevereto.

![Chevereto](LOGO.svg)

[![Community](https://img.shields.io/badge/chv.to-community-blue?style=flat-square)](https://chv.to/community)
[![Discord](https://img.shields.io/discord/759137550312407050?style=flat-square)](https://chv.to/discord)
[![Twitter Follow](https://img.shields.io/twitter/follow/chevereto?style=social)](https://twitter.com/chevereto)

<img src="https://chevereto.com/src/img/installer/screen-v2.png?20190623" style="max-height: 600px;">

## Description

The Installer is a single `.php` file that installs Chevereto. It is an API client which interacts with `chevereto.com` API for providing Chevereto installation.

## Features

This tool offers:

- HTTP / CLI API
- Checks for system requirements
- Automatic cPanel database setup
- Database checks
- Auto-generated Nginx server rules

## Reference

- [INSTALLING](INSTALLING.md)
- [HTTP API](HTTP.md)
- [CLI API](CLI.md)

## Nginx server rules

You can generate Nginx server rules on the fly with an HTTP GET.

```text
GET /installer.php?getNginxRules HTTP/1.1
Host: localhost
```
