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
The cURL options rules apply to this package
* Individual cURL options are not validated with this package
* cURL options can be managed by the following methods:
```
add_option - adds a new option
clear_options - clears all previous options
delete_options - deletes a specified option
get_options - gets the current options specified as an array
```
* The add_option(...) method will ignore a new option if the option has already been established
* Object cURL options can be set with the class constructor to establish a starting point
* Each HTTP Method (GET, POST, etc) will set the basic cURL options for class call convenience
* Review the link below for available cURL options available for each REST call
```
https://www.php.net/manual/en/function.curl-setopt.php
```
