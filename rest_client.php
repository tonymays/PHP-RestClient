<?php
declare(strict_types = 1);

define('ADDITEM', 0);
define('UPDATEITEM', 1);
define('IGNOREITEM', 2);

class RESTClient {
	private $curl_opts;

	public function __construct(array $curl_opts = []) {
		$this->curl_opts = [];
	}

	public function __destruct() {
		$this->curl_opts = null;
	}

	final public function set_option(string $option, string $value) : bool {
		$op = $this->array_add_status($this->curl_opts, $option, $value);
		switch ($op['status']) {
			case ADDITEM:
				$tmp = ['label'=>$option, 'label_value'=>$value];
				array_push($this->curl_opts, $tmp);
				return true;
				break;
			case UPDATEITEM:
				$this->curl_opts[$op['key']]['label_value'] = $value;
				return true;
				break;
			default:
				return false;
		}
	}


	final public function get_options() : array {
		return $this->curl_opts;
	}


	private function array_add_status(array $array, string $label, string $label_value) : array {
		$result = [
			'status' => ADDITEM,
			'key'	=> -1
		];

		if (count($array) > 0) {
			foreach($array as $key=>$value) {
				if ($value['label'] == $label) {
					if ($value['label_value'] === $label_value) {
						$result['status'] = IGNOREITEM;
					} else {
						$result['status'] = UPDATEITEM;
						$result['key'] = $key;
					}
					break;
				}
			}
		}
		return $result;
	}



	private function is_curl_enabled() :bool {
		return (
			function_exists('curl_init') &&
			function_exists('curl_setopt') &&
			function_exists('curl_exec') &&
			function_exists('curl_close')
		);
	}
}