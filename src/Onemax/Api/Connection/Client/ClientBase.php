<?php
namespace Onemax\Api\Connection\Client;
use Onemax\Api\Connection\Client\ClientInterface;
abstract class ClientBase implements ClientInterface
{
	protected $configs = array();
	protected $client = null;
	protected function __construct( $configs ) 
	{
		$this->configs = array_merge($this->configs, $configs);
	}
	public function getClient() 
	{
		return $this->client;
	}
}
