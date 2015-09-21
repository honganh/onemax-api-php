<?php
namespace Onemax\Api\Connection\Connector;

use GuzzleHttp\Exception\RequestException;
use Onemax\Api\Connection\Exception\OnemaxException;
use Onemax\Api\Connection\Client\ClientBase;
use Onemax\Api\Connection\Connector\ConnectorInterface;
class Connector implements ConnectorInterface
{
	/**
	 * Guzzle client
	 *
	 * @var \GuzzleHttp\Client
	 */
	protected $client = null;

	public function __construct(ClientBase $client ) 
	{
		if( ! $client instanceof ClientBase ) {
			throw new OnemaxException('Client must be instance of class Onemax\Api\Connection\Client\ClientBase', 1);
		}
		$this->client = ($client != null) ? $client->getClient() : null;
	}

	public function post($url, $data = array() ) 
	{
		return $this->_request('post', $url, $data);
	}
	public function get($url, $option = array() ) 
	{
		return $this->_request('get', $url, $option);
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
				'status'  => 'error', 
				'code'    => 500, 
				'message' => 'Internal error',
				'server'  => $_SERVER['SERVER_ADDR'], 
				'time'    => time(), 
				'version' => 1,
				'errors'  => []
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
			$httpResponse = ($method == 'get') ? $this->client->{$method}($url,$options) : $this->client->{$method}($url,['body' => $options]);
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
