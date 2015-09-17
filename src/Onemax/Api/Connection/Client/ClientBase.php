<?php
namespace Onemax\Api\Connection\Client;
abstract class BaseClient {
	protected $configs = array(
		'base_url'         => 'https://api.onemax.com.vn',
		'consumer_key'     => '',
		'consumer_secret'  => ''
	);
	protected $client ;
	protected function __construct( $configs ) {
		$this->configs = array_merge($this->configs, $configs);
	}
}