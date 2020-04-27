# Relations Messenger Client Bundle

This package provides `relmsg/client` package features for applications designed via Symfony framework

Client uses the specific version when sends messages to Core.

![Package version](https://img.shields.io/packagist/v/relmsg/client-bundle?style=for-the-badge)
![Client version](https://img.shields.io/static/v1?label=Client&message=1.0&color=blue&style=for-the-badge)
![Core version](https://img.shields.io/static/v1?label=Core&message=1.0&color=blue&style=for-the-badge)
![PHP Version](https://img.shields.io/static/v1?label=PHP&message=^7.4&color=blue&style=for-the-badge)

## Requirements

0. PHP 7.4+

## Installation

0. You will need Composer to install:
`composer require relmsg/client-bundle`
0. Register the bundle in `config/bundles.php` (Symfony Flex configures it automatic).
0. Configure the application authorization (see [Authorization](#authorization))

## Authorization

How to get authorization credentials for your application described [here](https://dev.relmsg.ru/authorization).

You have two options for setting authorization credentials: using environment variables and setting credentials directly in the package configuration.

To configure using environment variables, you need to use the `symfony/dotenv` package. Set the environment variables `RM_APP_ID` and` RM_APP_SECRET` in one of the `.env` files:
```dotenv
RM_APP_ID=paste-your-app-id
RM_APP_SECRET=paste-your-app-secret
```

Or in the package config you can set the `app_id` and `app_secret` properties:
```yaml
relmsg_client:
    auth:
        app_id: paste-your-app-id
        app_secret: paste-your-app-secret
```
