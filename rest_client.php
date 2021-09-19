<?php
declare(strict_types = 1);

class RESTClient {
	// ---- class properites ----
	private $headers;	// array to hold call headers
	private $options;	// array to hold curl options

	// ----__construct ----
	// class constructor
	public function __construct() {
		if (! $this->curl_enabled()) {
			throw new Exception('critical error: curl is not enabled, installed or available');
		}
		$this->headers = [];
		$this->options = [];
	}

	// ---- __destruct ----
	// class destructor
	public function __destruct() {
		$this->headers = null;
		$this->options = null;
	}

	// ---- execute ----
	// protected method that executes a curl call
	// takes a string parameter that represent the url
	// returns a response array or throws a potential error
	protected function execute(string $url) : array {
		try {
			// fail if the url is missing
			if ($url === '') {
				throw new Exception('critical error: request is missing the url');
			}

			// initialize curl and set the options given
			$handle = curl_init($url);

			// set the options if we have them
			if (count($this->options) > 0) {
				curl_setopt_array($handle, $this->options);
			}

			// set the headers - this is how headers are established
			curl_setopt($handle, CURLOPT_HTTPHEADER, $this->transform_headers());

			// setup the header callback method
			$headers = [];
			curl_setopt($handle, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headers) {
					$len = strlen($header);
					$header = explode(':', $header, 2);
					if (count($header) < 2) return $len;
					$headers[strtolower(trim($header[0]))] = trim($header[1]);
					return $len;
				}
			);

			// execute the rest call
			$response = curl_exec($handle);

			// return response
			return ['http_code'=>curl_getinfo($handle, CURLINFO_HTTP_CODE), 'headers'=>$headers, 'response'=>$response];
		} catch (Exception $e) {
			// throw any errors
			throw $e;
		} finally {
			// and finally, close the handle
			curl_close($handle);
		}
	}

	// ---- post ----
	// make a POST REST call
	// takes two parameters: a striing that represents the url and a string representing the post body
	// returns a response array
	final public function post(string $url, string $body = '') : array {
		return $this->make_call($url, 'POST', $body);
	}

	// ---- patch ----
	// make a PATCH REST call
	// takes two parameters: a striing that represents the url and a string representing the patch body
	// returns a response array
	final public function patch(string $url, string $body = '') : array {
		return $this->make_call($url, 'PATCH', $body);
	}

	// ---- put ----
	// make a PUT REST call
	// takes two parameters: a striing that represents the url and a string representing the put body
	// returns a response array
	final public function put(string $url, string $body = '') : array {
		return $this->make_call($url, 'PUT', $body);
	}

	// ---- get ----
	// make a GET REST call
	// takes one parameter: a striing that represents the url
	// returns a response array
	final public function get(string $url) : array {
		return $this->make_call($url, 'GET');
	}

	// ---- delete ----
	// make a DEKETE REST call
	// takes one parameter: a striing that represents the url
	// returns a response array
	final public function delete(string $url) : array {
		return $this->make_call($url, 'DELETE');
	}

	// ---- head ----
	// make a HEAD REST call
	// takes one parameter: a striing that represents the url
	// returns a response array
	final public function head(string $url) : array {
		return $this->make_call($url, 'HEAD');
	}

	// ---- options ----
	// make a OPTIONS REST call
	// takes one parameter: a striing that represents the url
	// returns a response array
	final public function options(string $url) : array {
		return $this->make_call($url, 'OPTIONS');
	}

	// ---- set_header ----
	// sets a header
	// takes two parameters: a string representing the header and a string representing the header value
	// has no return value ... see get_headers to assurances
	final public function set_header(string $header, string $value) : void {
		$this->set($this->headers, $header, $value);
	}

	// ---- get_headers ----
	// get the current headers as an array
	final public function get_headers() : array {
		print_r($this->transform_headers());
		return $this->headers;
	}

	// ---- set_options ----
	// sets a curl option
	// takes two parameters: an int representing the option and a mixed value representing the option value
	// NOTE: set true to 1 and false to 0
	// has no return value ... see get_headers to assurances
	final public function set_option(int $option, $value) : void {
		$this->set($this->options, $option, $value);
	}

	// ---- get_options ----
	// get the current curl options as an array
	final public function get_options() : array {
		return $this->options;
	}

	// ---- make_call ----
	// private method that sets up a call and then executes the call
	// takes three parameters:
	//		a string that represents the url
	//		a string that represents the call method
	//		a string that represents the call body
	// returns a response array
	private function make_call(string $url, string $method, string $body = '') : array {
		// take a snapshot of the current options
		$options = $this->options;

		// set the return transfer option
		$this->set_option(CURLOPT_RETURNTRANSFER, 1);

		// setup default options for each call type
		switch ($method) {
			case 'POST':
				$this->set_option(CURLOPT_POST, 1);
				$this->set_option(CURLOPT_POSTFIELDS, $body);
				break;
			case 'PATCH':
				$this->set_option(CURLOPT_CUSTOMREQUEST, 'PATCH');
				$this->set_option(CURLOPT_POSTFIELDS, $body);
				break;
			case 'PUT':
				$this->set_option(CURLOPT_CUSTOMREQUEST, 'PUT');
				$this->set_option(CURLOPT_POSTFIELDS, $body);
				break;
			case 'GET':
				break;
			case 'DELETE':
				$this->set_option(CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
			case 'HEAD':
				$this->set_option(CURLOPT_CUSTOMREQUEST, 'HEAD');
				break;
			case 'OPTIONS':
				$this->set_option(CURLOPT_CUSTOMREQUEST, 'OPTIONS');
				break;
		}

		// execute the call
		$result = $this->execute($url);

		// set options back to their pre-call state for the next potential call
		$this->options = $options;

		// return the response array
		return $result;
	}

	// ---- set ----
	// sets an element on an associative array and is used to set headers and options generically
	// there is no return from this call since the specified array in updated by this call
	private function set(array &$array, $key, $value) : void {
		// does the key in the specified array exists ...
		if (array_key_exists($key, $array)) {
			// does the element value differ from the specified value, otherwise, ignore operation...
			if ($array[$key] !== $value) {
				// then update the value.
				$array[$key] = $value;
			}
		// if the key cannot be found then ...
		} else {
			// add the element to the array in a way that preserves numerical keys
			$array = $array + [$key => $value];
		}
	}

	// ---- transform_headers ----
	// transfers headers from an associative array to an index array
	// returns an index array of headers
	private function transform_headers() : array {
		$result = [];
		foreach ($this->headers as $key=>$value) {
			$result = array_merge($result, [$key . ': ' . $value]);
		}
		return $result;
	}

	// ---- curl_enabled ----
	// a private method that tests if curl is enabled, installed and available
	// returns true if curl is available; otherwise, returns false
	private function curl_enabled() : bool {
		// look for the functions this class will use to execute curl calls with
		return (
			function_exists('curl_init') &&
			function_exists('curl_setopt') &&
			function_exists('curl_exec') &&
			function_exists('curl_close')
		);
	}
}


