# Re Message Client Bundle

This package provides [`remessage/client`](https://github.com/re-message/client) package features for applications designed via [Symfony](https://symfony.com) framework.

Client uses the specific version when sends messages to Core. You cannot change the version of the API used, because this may violate the logic of this package.

![Package version](https://img.shields.io/packagist/v/remessage/client-bundle?style=for-the-badge)
![Client version](https://img.shields.io/static/v1?label=Client&message=^0.8.1&color=blue&style=for-the-badge)
![Core version](https://img.shields.io/static/v1?label=Core&message=1.0&color=blue&style=for-the-badge)
![PHP Version](https://img.shields.io/static/v1?label=PHP&message=^8.1&color=blue&style=for-the-badge)

## Requirements

1. PHP 8.1+
2. Requirements of [`remessage/client`](https://github.com/re-message/client)

## Installation

1. You will need Composer to install: `composer require remessage/client-bundle`
2. Register the bundles in `config/bundles.php`:
    * RmClientBundle
    * RmMessageBundle
3. Configure the application authorization (see [Authorization](#authorization))

## Authorization

How to get authorization credentials for your application described [here](https://dev.remessage.ru/authorization).

You have two options for setting authorization credentials: using environment variables and setting credentials directly in the package configuration.

To configure using environment variables, you need to use the `symfony/dotenv` package. Set the environment variables `RM_APP_ID` and` RM_APP_SECRET` in one of the `.env` files:
```dotenv
RM_APP_ID=paste-your-app-id
RM_APP_SECRET=paste-your-app-secret
```

Or in the package config you can set the `app_id` and `app_secret` properties:
```yaml
remessage_client:
    auth:
        app_id: paste-your-app-id
        app_secret: paste-your-app-secret
```

This configuration provides automatic authorization on each request, if the service token is not found in the storage.

If you do not want to authorize your application for each request, you can disable automatic authorization:
```yaml
remessage_client:
    auth:
        auto: false
```
This means that the parameters `app_id` and `app_secret` parameters will be injected in `RM\Component\Client\Security\Authenticator\ServiceAuthenticator` service, but the `authenticate` method will not be called. So you can call this method when you need.

Otherwise, if you do not need any of these behaviors, you can disable this behavior completely:
```yaml
remessage_client:
    auth: false
```
