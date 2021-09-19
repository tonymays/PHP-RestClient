<?php
declare(strict_types = 1);

// ---- define class constants ----
define('ADD_ITEM', 0);
define('UPDATE_ITEM', 1);
define('IGNORE_ITEM', 2);
define('KEY_NOT_FOUND', -1);

class RESTClient {
	// ---- class properites ----
	private $headers;		// array to hold call headers
	private $curl_opts;		// array to hold curl options

	// ---- __construct ----
	// class constructor
	// can take two optional parameters: headers and curl_opts
	public function __construct(array $headers = [], array $curl_opts = []) {
		try {
			if (!$this->curl_enabled()) {
				throw new Exception('critical error: cURL is not enabled, installed or available');
			}

			// initialize and set both the headers and curl_opts arrays
			$this->headers = [];
			$this->curl_opts = [];
			$this->init_headers($headers);
			$this->init_options($curl_opts);
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- __destruct ----
	// class destructor
	public function __destruct() {
		$this->curl_opts = null;
		$this->headers = null;
	}

	// ---- set_header ----
	// attempts to add or update a header
	// takes two parameters: $option and $value
	// $option contains a string representing the header
	// $value contains a string representing the value of the header
	// returns a bool
	final public function set_header(string $option, string $value) : bool {
		try {
			$result = true;
			// determine if we are adding a new entry, updating and existing entty,
			// or, ignoring the header
			$op = $this->get_array_add_status($this->headers, $option, $value);
			switch ($op['status']) {
				case ADD_ITEM:
				array_push($this->headers, ['label'=>$option, 'label_value'=>$value]);
				break;
				case UPDATE_ITEM:
				$this->headers[$op['key']]['label_value'] = $value;
				break;
				default:
				$result = false;
			}
			return false;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- set_option ----
	// attempts to add or update a curl option
	// takes two parameters: $option and $value
	// $option contains a string representing the curl option
	// $value contains a string representing the value of the curl option
	// returns a bool
	final public function set_option(string $option, string $value) : bool {
		try {
			$result = true;
			// determine if we are adding a new entry, updating and existing entty,
			// or, ignoring the option
			$op = $this->get_array_add_status($this->curl_opts, $option, $value);
			switch ($op['status']) {
				case ADD_ITEM:
				array_push($this->curl_opts, ['label'=>$option, 'label_value'=>$value]);
				break;
				case UPDATE_ITEM:
				$this->curl_opts[$op['key']]['label_value'] = $value;
				break;
				default:
				$result = false;
			}
			return false;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- delete_header ----
	// attempts to delete a header
	// takes one parameter: $header
	// $header contains a string representing the header you wish to delete
	// returns a bool
	final public function delete_header(string $header) : bool {
		try {
			// determine if the specified header exists
			$op = $this->get_array_delete_status($this->headers, $header);
			// delete the header if a key can be found for the header
			if ($op['key'] !== KEY_NOT_FOUND) {
				unset($this->headers[$op['key']]);
				array_values($this->headers);
				return true;
			}
			return false;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- delete_option ----
	// attempts to delete a option
	// takes one parameter: $option
	// $option contains a string representing the option you wish to delete
	// returns a bool
	final public function delete_option(string $option) : bool {
		try {
			// determine if the specified option exists
			$op = $this->get_array_delete_status($this->curl_opts, $option);
			// delete the option if a key can be found for the option
			if ($op['key'] !== KEY_NOT_FOUND) {
				unset($this->curl_opts[$op['key']]);
				array_values($this->curl_opts);
				return true;
			}
			return false;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// --- clear_headers ----
	// clears all request headers
	final public function clear_headers() : bool {
		try {
			$this->headers = [];
			return (count($this->headers) == 0);
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- clear_options ----
	// clears all curl options
	final public function clear_options() : bool {
		try {
			$this->curl_opts = [];
			return (count($this->curl_opts) == 0);
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- get_headers ----
	// returns all curl headers that have been set
	// no parameters
	// returns an array
	final public function get_headers() : array {
		try {
			return $this->headers;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- get_options ----
	// returns all curl options that have been set
	// no parameters
	// returns an array
	final public function get_options() : array {
		try {
			return $this->curl_opts;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- set_headers ----
	// replace headers with the specified array
	// takes one parameter: $headers
	// $headers contains an array of headers to add
	// returns a bool
	final public function set_headers(array $headers) : bool {
		try {
			// fail if the specified headers array is not an associative array
			if (!$this->is_array_associative($headers)) {
				return false;
			}
			// clear the headers array if the new headers array is in acceptable format
			$this->clear_headers();
			// add each header
			foreach($headers as $key=>$value) {
				$this->set_header($key, $value);
			}
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- set_options ----
	// replace options with the specified array
	// takes one parameter: $options
	// $options contains an array of options to add
	// returns a bool
	final public function set_options(array $options) : bool {
		try {
			// fail if the specified options array is not an associative array
			if (!$this->is_array_associative($options)) {
				return false;
			}
			// clear the options array if the new options array is in acceptable format
			$this->clear_options();
			// add each option
			foreach($options as $key=>$value) {
				$this->set_option($key, $value);
			}
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- init_headers ----
	// private worker function that establishes headers during class construction
	private function init_headers(array $headers) : void {
		try {
			if (count($headers) > 0) {
				if (!$this->set_headers($headers)) {
					throw new Exception('critical error: invalid header array specified');
				}
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- init_options ----
	// private worker function that establishes options during class construction
	private function init_options(array $options) : void {
		try {
			if (count($options) > 0) {
				if (!$this->set_options($options)) {
					throw new Exception('critical error: invalid options array specified');
				}
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- transform_headers ----
	// transforms header from an associative array to an index array for call execution
	// CURLOPT_HTTPHEADER cURL option takes an indexed array of headers
	private function transform_headers() : array {
		try {
			$result = [];
			if (count($this->headers) > 0) {
				foreach($this->headers as $key=>$value) {
					array_push($result, $value['label'] . ': ' . $value['label_value']);
				}
			}
			return $result;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- get_array_add_status ----
	// a private worker function that scans the given associative array and determines
	// if a set operation will add a new item, update an existing item or simply
	// ignore the request
	private function get_array_add_status(array $array, string $label, string $label_value) : array {
		try {
			$result = [
				'status' => ADD_ITEM,
				'key' => KEY_NOT_FOUND,
			];
			// walk the array if we have array contents, otherwise, we are adding a new item
			if (count($array) > 0) {
				foreach($array as $key=>$value) {
					// if the array labels match...
					if ($value['label'] == $label) {
						// ... and the array label values match ...
						if ($value['label_value'] === $label_value) {
							// ... then the request becasue the request already exists.
							$result['status'] = IGNORE_ITEM;
						} else {
							// ... or, update the request becasue the request exists
							// but with a different value
							$result['status'] = UPDATE_ITEM;
							$result['key'] = $key;
						}
						break;
					}
				}
			}
			return $result;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- get_array_delete_status ----
	private function get_array_delete_status(array $array, string $label) : array {
		try {
			$result = [
				'key' => KEY_NOT_FOUND,
			];
			// if the specified array is not empty, then walk the array
			if (count($array) > 0) {
				foreach($array as $key=>$value) {
					// if we found the specified label...
					if ($value['label'] == $label) {
						// ... then record its location
						$result['key'] = $key;
						break;
					}
				}
			}
			return $result;
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- is_array_associative ----
	// private worker function that determines if the specified array is associative
	private function is_array_associative($array) {
		try {
			if (empty($array)) {
				return false;
			}
			return array_keys($array) !== range(0, count($array) -1);
		} catch (Exception $e) {
			throw $e;
		}
	}

	// ---- curl_enabled ----
	// returns true if curl is enabled and available; otherwise, returns false
	private function curl_enabled() :bool {
		try {
			return (
				function_exists('curl_init') &&
				function_exists('curl_setopt') &&
				function_exists('curl_exec') &&
				function_exists('curl_close')
			);
		} catch (Exception $e) {
			throw $e;
		}
	}
}