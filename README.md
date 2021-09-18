# PHP-RestClient
RESTful client for PHP

```
Built on PHP 7.4.23
Zend Engine v3.4.0

This class is not backwards compatible to PHP 5 because it uses scalar and return
type declarations
```

## Package Requirements
* Each REST call is a separate call
* Results from previous calls are your responsibility to manage to and including headers, bodies and options
* This package will throw only those errors that prevent a REST call from being made or response received

## cURL Options
cURL options will be managed given the following:
* Individual cURL options are not validated
* Each HTTP Method (GET, POST, etc) will set appropriate cURL options
* &emsp;POST
```
```
* Review the link below for available cURL options available for each REST call
```
https://www.php.net/manual/en/function.curl-setopt.php
```
