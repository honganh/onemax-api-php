<?php
namespace Onemax\Api\Connection;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\json_decode;
use GuzzleHttp\Client;
use Onemax\Api\Connection\OnemaxException; 
class Connector 
{
	/**
	 * Guzzle client
	 *
	 * @var \GuzzleHttp\Client
	 */
	protected $client = null;

	protected $configs = array(
		'base_url'         => 'https://api.onemax.com.vn',
		'consumer_key'     => '',
		'consumer_secret'  => ''
	);
	public function __construct($configs = array() ) 
	{
		$this->config = array_merge($this->configs, $configs);
		
		$args = [
			'base_url' => $this->configs['base_url'], 
			'headers'  => ['Content-Type' => 'application/json'],
			'defaults' => ['auth' => 'oauth', 'verify'   => false] ,
		];
		$oauth = new Oauth1([
			'consumer_key'    => $this->configs['consumer_key'],
			'consumer_secret' => $this->configs['consumer_key'],
		]);
		$this->client = new Client($args);
		$this->client->getEmitter()->attach($oauth);
	}

	public function post($url, $data = array() ) 
	{
		return $this->_request('post', $url, $data);
	}
	public function get($url, $option = array() ) 
	{
		return $this->_request('get', $url, $data);
	}
	public function delete($url, $data = array() ) 
	{
		return $this->_request('delete', $url, $data);
	}
	public function put($url, $data = array() ) 
	{
		return $this->_request('put', $url, $data);
	}
	private function _errorDefault() {
		$response = [
			'result' => [
				'status' => 'error', 'code' => 500, 'message' => 'Internal error',
				'server' => $_SERVER['SERVER_ADDR'], 'time' => time(), 'version' => 1,
				'errors' => []
			]
		];
		return @json_encode($response);
	}
	private function _request($method = 'get', $url, $options) {
		$content    = null;
		$statusCode = null;
		$message    = '';
		$method = strtolower( trim($method) );
		try 
		{
			$httpResponse = ($method == 'get') ? $this->client->{$method}($url,$data) : $this->client->{$method}($url,['body' => $data]);
			$statusCode = $httpResponse->getStatusCode();
			$body = $httpResponse->getBody();
			$unreadBytes = $body->getMetadata()['unread_bytes'];
			if($unreadBytes > 0) 
			{
				$content = $body->getContents();
			} 
			else 
			{
				$content = (string)$body;
			}
		} 
		catch(RequestException $re)  {
			if($re->hasResponse()) 
			{
				$httpErrorResponse = $re->getResponse();
				$statusCode = $httpErrorResponse->getStatusCode();
				$body = $httpErrorResponse->getBody();
				$unreadBytes = $body->getMetadata()['unread_bytes'];
				if($unreadBytes > 0) 
				{
					$content = $body->getContents();
				} 
				else 
				{
					$content = (string)$body;
				}
				
			}
		}
		catch(Exception $e) {
			$content = '';
		}
		if(empty($content)) {
			$content = $this->_errorDefault();
		}
		return $content;
	}
}
