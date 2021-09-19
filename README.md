# PHP-RestClient
RESTful client for PHP

```
Built on PHP 7.4.23
Zend Engine v3.4.0

The simplest most powerful rest client for PHP

This class is not backwards compatible to PHP 5 because it uses scalar and return type declarations
```
## Class Usage
The fastest way to learn what is in a class is to see it in action

A sample of simplicity
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
$response = $c->post("http://45.55.49.63:8080/auth", $data);
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
            [auth-token] => eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImFudGhvbnkuZC5tYXlzQGdtYWlsLmNvbSIsImV4cCI6MTYzMjA5Nzg0NSwicmVtb3RlX2FkZHIiOiIxOTIuMjQxLjE1MS41OCIsInVzZXJfaWQiOiIwMGNkMjQ2OS1kNTk3LTQ4YWMtYTg2NC0xNGFhODAwMDkxMjciLCJ1c2VybmFtZSI6InJvb3QifQ.SWNTQ0b5EwMCIhxCj55oCHG82L5bCwnH-E3g2xDxhpI
            [content-type] => application/json; charset=UTF-8
            [date] => Sun, 19 Sep 2021 23:30:45 GMT
            [content-length] => 207
        )
    [response] => {"user_idid":"00cd2469-d597-48ac-a864-14aa80009127","username":"root","email":"anthony.d.mays@gmail.com","remote_addr":"192.241.151.58","service_catalog":["Can edit role","Can Edit User","Can Delete User"]}
)
```

The above code focuses on four things:
* setting the call headers
* establishing a post body
* making the call
* displaying the results of the call
