# PHP-RestClient
RESTful client for PHP

```
Built on PHP 7.4.23
Zend Engine v3.4.0

Simply the easy most powerful REST API Client for PHP.

This was written for PHP 7+ but can easily be forked for Version 5 Versions of PHP.
```
## Class Usage
The fastest way to learn what is in a class is to see it in action

A sample of simplicity
```
<?php
require_once('rest_client.php');
$c = new RESTClient();
$c->set_header('Content-Type', 'application/json');
$data = <<<DATA{"username": "somebody","password": "somepassword"}DATA;
$response = $c->post("http://127.0.0.0:8080/auth", $data);
print_r($response);
```

Outputs
```bash
Array
(
    [http_code] => 200
    [headers] => Array
        (
            [access-control-allow-headers] => Content-Type, Auth-Token, API-Key
            [access-control-allow-methods] => HEAD,GET,DELETE,POST,PATCH,PUT
            [access-control-allow-origin] => *
            [access-control-expose-headers] => Content-Type, Auth-Token, API-Key
            [auth-token] => efggbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImFudGhvbnkuZC5tYXlzQGdtYWlsLmNvbSIsImV4cCI6MTYzMjA5Nzg0NSwicmVtb3RlX2FkZHIiOiIxOTIuMjQxLjE1MS41OCIsInVzZXJfaWQiOiIwMGNkMjQ2OS1kNTk3LTQ4YWMtYTg2NC0xNGFhODAwMDkxMjciLCJ1c2VybmFtZSI6InJvb3QifQ.SWNTQ0b5EwMCIhxCj55oCHG82L5bCwnH-E3g2xDxhpI
            [content-type] => application/json; charset=UTF-8
            [date] => Sun, 19 Sep 2021 23:30:45 GMT
            [content-length] => 207
        )
    [response] => {"user_idid":"11cd2469-d597-48ac-a864-14aa80009127","username":"somebody","email":"somebody@gmail.com","remote_addr":"127.0.0.0","service_catalog":["Can edit role","Can Edit User","Can Delete User"]}
)
```

The above code focuses on four things:
* setting the call headers
* establishing a post body
* making the call
* displaying the results of the call

Notice in the output of the above call that there is a auth-token header returned.  The auth-token is a JWT token and needs to be set appropriately for future calls.

So let's make the next call now that we have our JWT Token from the login attempt
```
$c->set_header('Auth-Token', $response['headers']['auth-token']);
$response = $c->get("http://127.0.0.1:8080/users");
print_r(json_decode($response['response'], true));
```

Below is the whole call code from login to getting users displayed
```
<?php
require_once('rest_client.php');
$c = new RESTClient();
$c->set_header('Content-Type', 'application/json');
$data = <<<DATA
{
	"username": "root",
	"password": "abc123xyz890"
}
DATA;
$response = $c->post("http://127.0.0.1:8080/auth", $data);
$c->set_header('Auth-Token', $response['headers']['auth-token']);
$response = $c->get("http://127.0.0.1:8080/users");
print_r(json_decode($response['response'], true));
```

And outputs the following:
```bash
Array
(
    [0] => Array
        (
            [user_id] => 11cd2469-d597-48ac-a864-14aa80009127
            [username] => someuser
            [first_name] => John
            [last_name] => Doe
            [address] => 123 ABC Ave
            [city] => Bassett
            [state] => NH
            [zip] => 12345
            [country] => United States
            [email] => somebody@gmail.com
            [phone] => 1234567679
            [active] => Yes
            [created] => 2021-09-15 19:58:01.597137168 +0000 UTC
            [modified] => 2021-09-15 21
        )

    [1] => Array
        (
            [user_id] => 5d40a6f2-de58-43c6-9992-7fba3198ba16
            [username] => someotheruser
            [first_name] => Jane
            [last_name] => Doe
            [address] => 123 ABC Ave
            [city] => Bassett
            [state] => NH
            [zip] => 12345
            [country] => United States
            [email] => somebodyelse@gmail.com
            [phone] => 1234567665
            [active] => Yes
            [created] => 2021-09-15 19:58:01.597137168 +0000 UTC
            [modified] => 2021-09-15 21
        )

)
```

## Class Methods
Below are the public and protected methods of this class.

## Make that Call
The following methods offer the ability to make RESTful calls

POST
* final public function post(string $url, string $body = '') : array
* make a POST REST call
* takes two parameters: a string that represents the url and a string representing the post body
* returns a response array
* establishes the following curl options as call defaults:
```
$this->set_option(CURLOPT_RETURNTRANSFER, 1);
$this->set_option(CURLOPT_POST, 1);
$this->set_option(CURLOPT_POSTFIELDS, $body);
```

PATCH
* final public function patch(string $url, string $body = '') : array
* make a PATCH REST call
* takes two parameters: a string that represents the url and a string representing the patch body
* returns a response array
* establishes the following curl options as call defaults:
```
$this->set_option(CURLOPT_RETURNTRANSFER, 1);
$this->set_option(CURLOPT_CUSTOMREQUEST, 'PATCH');
$this->set_option(CURLOPT_POSTFIELDS, $body);
```

PUT
* final public function put(string $url, string $body = '') : array
* make a PUT REST call
* takes two parameters: a string that represents the url and a string representing the put body
* returns a response array
* establishes the following curl options as call defaults:
```
$this->set_option(CURLOPT_RETURNTRANSFER, 1);
$this->set_option(CURLOPT_CUSTOMREQUEST, 'PUT');
$this->set_option(CURLOPT_POSTFIELDS, $body);
```

GET
* final public function get(string $url) : array
* make a GET REST call
* takes one parameter: a string that represents the url
* returns a response array
* establishes the following curl options as call defaults:
```
$this->set_option(CURLOPT_RETURNTRANSFER, 1);
```

DELETE
* final public function delete(string $url) : array
* make a DEKETE REST call
* takes one parameter: a string that represents the url
* returns a response array
* establishes the following curl options as call defaults:
```
$this->set_option(CURLOPT_RETURNTRANSFER, 1);
$this->set_option(CURLOPT_CUSTOMREQUEST, 'DELETE');
```

HEAD
* final public function head(string $url) : array
* make a HEAD REST call
* takes one parameter: a string that represents the url
* returns a response array
* establishes the following curl options as call defaults:
```
$this->set_option(CURLOPT_RETURNTRANSFER, 1);
$this->set_option(CURLOPT_CUSTOMREQUEST, 'HEAD');
```

OPTIONS
* final public function options(string $url) : array
* make a OPTIONS REST call
* takes one parameter: a string that represents the url
* returns a response array
* establishes the following curl options as call defaults:
```
$this->set_option(CURLOPT_RETURNTRANSFER, 1);
$this->set_option(CURLOPT_CUSTOMREQUEST, 'OPTIONS');
```

## Manage Call Headers
The following methods assist in managing call headers

final public function set_header(string $header, string $value) : void
* sets a header
* takes two parameters: a string representing the header and a string representing the header value
* has no return value ... see get_headers to assurances

final public function clear_headers() : void
* clears the internal headers array
* the method takes no parameters and has no return type
* use get_headers() for assurances

final public function delete_header(string $header) : void
* removes a header from the internal headers array
* takes one parameter: a string representing the header to the remove
* this method has no return type ... use get_headers() for assurances

final public function get_headers() : array
* get the current headers as an array


## Manage Call cURL Options
The following methods assist in manage call cURL options

final public function set_option(int $option, $value) : void
* sets a curl option
* takes two parameters: an int representing the option and a mixed value representing the option value
* NOTE: set true to 1 and false to 0
* has no return value ... see get_headers to assurances

final public function clear_options() : array
* clears the internal options array
* the method takes no parameters and has no return type
* user get_options() for assurances

final public function delete_option(int $option) : void
* removes a option from the internal options array
* takes one parameter: an int representing the option to the remove
* this method has no return type ... use get_options() for assurances

final public function get_options() : array
* get the current curl options as an array