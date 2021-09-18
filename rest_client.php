<?php
declare(strict_types = 1);

class RESTClient {
	private $curl_opts;

	public function __construct(array $curl_opts = []) {
		$this->curl_opts = [];
	}

	public function __destruct() {
		$this->curl_opts = null;
	}





	// privates

	private function is_curl_enabled() :bool {
		return (
			function_exists('curl_init') &&
			function_exists('curl_setopt') &&
			function_exists('curl_exec') &&
			function_exists('curl_close')
		);
	}
}