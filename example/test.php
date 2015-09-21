<?php
use Onemax\Api\Connection\Merchant;
$configs = array(
	'base_url'     => 'http://api-sandbox.onemax.com.vn',
	'consumer_key'    => '',
	'consumer_secret' => ''
);

$merchant = new Merchant( $configs );