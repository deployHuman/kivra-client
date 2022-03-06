# PHP Client for Kivra

- API version: v2 and V1

Is suppose to be a simple client to access a Swedish company named Kivra´s API.

All help is appreciated


### Composer

To install using [Composer](http://getcomposer.org/)

Just type 

`composer require deployhuman/kivra-client dev-main`

And you are good!


## Getting Started


```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

//starting a Config Instance, setting up the bare minimum
$Config = (new \DeployHuman\Configuration())
  ->setClient_id('Your API pre-known Client Id')
  ->setClient_secret('Your API pre-known Client Secret')

//add config to the Client to create and api instance
$apiInstance = new \DeployHuman\ApiClient($Config);

//from this instance you can now call All the Kivra API´s ****
$fetchArray = $apiInstance->TenantManagement()->callAPIListAllTenantsAccessibleToTheClient();

?>
```

## Documentation for API Endpoints

*** = I´m slowly adding new calls, first its only going to be the basics one.

All URIs are relative to *https://sender.api.kivra.com

