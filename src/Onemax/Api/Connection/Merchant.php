<?php
namespace Onemax\Api\Connection;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\json_decode;
use Onemax\Api\Connection\Untils\UrlBuilder;
use Onemax\Api\Connection\Untils\Decoder;
use Onemax\Api\Connection\Connector;
use Onemax\Api\Connection\ConnectorInterface;


class Merchant extends Connector 
{
	protected $header = array();
	protected $body   = array();
	protected $options= array();
	public function __construct($config ) 
	{
		$client = new ClientCredential( $config );
		parent::__construct($client);
	}
	// user
	public function get( $url='', &$error = null)
	{
		return Decoder::parse($this->get($url, array() ), $error) ;
	}
	public function post( $url='', $params=array(), &$error = null)
	{
		$url = UrlBuilder::build( $url, $params );
		return Decoder::parse($this->post($url, $params ), $error) ;
	}
	public static function json_decode($json, $assoc = false, $depth = 512, $options = 0)
	{
		static $jsonErrors = array(
            JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
            JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded'
        );

        $data = \json_decode($json, $assoc, $depth, $options);

        if (JSON_ERROR_NONE !== json_last_error()) 
        {
            $last = json_last_error();
            return null;
        }
        return $data;
	}
}
