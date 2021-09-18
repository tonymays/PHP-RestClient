# PHP-RestClient
RESTful client for PHP

```
Built on PHP 7.4.23
Zend Engine v3.4.0

This class is not backwards compatible to PHP 5 because it uses scalar and return
type declarations
```


## CURL OPTS
The cURL options rules apply to this package
* Individual cURL options are not validated with this package
* curl options can be managed by the following methods:
```
add_option - adds a new option
clear_options - clears all previous options
delete_options - deletes a specified option
get_options - gets the current options specified as an array
```
* Object cURL Options can be set with the class constructor to establish a starting point
* Each HTTP Method (GET, POST, etc) will set the basic cURL options for class call convenience
* Review the link below for available cURL options options available
```
https://www.php.net/manual/en/function.curl-setopt.php
```
