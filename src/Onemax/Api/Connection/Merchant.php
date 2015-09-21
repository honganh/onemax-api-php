<?php
namespace Onemax\Api\Connection;

use Onemax\Api\Connection\Untils\UrlBuilder;
use Onemax\Api\Connection\Untils\Decoder;
use Onemax\Api\Connection\Connector\Connector;
use Onemax\Api\Connection\OnemaxInterface;
use Onemax\Api\Connection\Client\OAuth1;

class Merchant extends Connector implements OnemaxInterface
{
	public function __construct($config ) 
	{
		$client = new OAuth1( $config );
		parent::__construct($client);
	}
}
