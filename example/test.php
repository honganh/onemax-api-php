<?php
use Onemax\Api\Connection\Merchant;
$configs = array(
	'base_url'     => 'http://api.test.com',
	'consumer_key'    => 'consumer_key',
	'consumer_secret' => 'consumer_secret'
);

$merchant = new Merchant( $configs );
