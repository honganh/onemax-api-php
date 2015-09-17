<?php
namespace Onemax\Api\Connection;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\json_decode;
use Onemax\Api\Connection\UrlBuilder;
use Onemax\Api\Connection\Connector;
use Onemax\Api\Connection\ConnectorInterface;
use Onemax\Api\Connection\Decoder;

class Base extends Connector implements ConnectorInterface 
{
	protected $header = array();
	protected $body   = array();
	protected $options= array();
	public function __construct($config ) 
	{
		parent::__construct($config);
	}
	// user
	public function getListUser( &$error = null)
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.list') );
		return Decoder::parse($this->get($url, $this->header, $this->body, $this->options ), $error) ;
	}
	public function getUserByUsername( $username , &$error)
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.username'), array('username' => $username) );
		return Decoder::parse( $this->get($url, $this->header, $this->body, $this->options ), $error) ;
	}
	public function getUserByEmail( $email , &$error )
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.getbyemail'), array('email' => $email) );
		return Decoder::parse($this->get($url, $this->header, $this->body, $this->options ), $error);
	}
	public function createUser( $email, $username, $password , &$error)
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.create') );
		$body = array(
			'email'        => $email,
			'username'     => $username,
			'password'     => $password, // password encrypt sha 256
		);
		return Decoder::parse( $this->post($url, $this->header, $body, $this->options ), $error) ;
	}

	// profile
	public function getUserProfile( $userId, $provider='facebook', &$error )
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.profile'), array('uid' => $userId, 'provider' => $provider) );
		return Decoder::parse( $this->get($url, $this->header, $this->body, $this->options ), $error) ;
	}
	public function getProfileByEmail( $email , &$error)
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.list_email'), array('email' => $email) );
		return Decoder::parse( $this->get($url, $this->header, $this->body, $this->options ), $error) ;
	}
	public function getProfileByUsername( $username , &$error )
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.list_username' ), array('username' => $username ));
		return Decoder::parse( $this->get($url, $this->header, $this->body, $this->options ), $error );
	}
	public function createProfile( $email, $username, $password= '',$first_name = '', $last_name = '', $display_name = '', $referrer = '')
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.create_profile' ) );
		$body = array(
			'email'        => $email,
			'username'     => $username,
			'password'     => $password, // password encrypt sha 256
			'first_name'   => $first_name,
			'last_name'    => $last_name,
			'display_name' => $display_name,
			'referrer'     => $referrer
		);
		return $this->post($url, $this->header, $body, $this->options );
	}
	
	public function linkProfileByEmail( $email, &$error )
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.link_email') , array('email' => $email ) );
		return Decoder::parse( $this->post($url, $this->header, $this->body, $this->options ), $error) ;
	}
	public function linkProfileByUsername( $username , &$error )
	{
		$url = UrlBuilder::buildUrl(Config::get('api.user.link_username' ) , array('username' => $username ) );
		return Decoder::parse( $this->post($url, $this->header, $this->body, $this->options ), $error) ;
	}

	// game
	public function getListGame( &$error)
	{
		$url = UrlBuilder::buildUrl(Config::get('api.game.list') );
		return Decoder::parse( $this->get($url, $this->header, $this->body, $this->options ), $error) ;
	}
	public function getGame($gameId, &$error )
	{
		$url = UrlBuilder::buildUrl(Config::get('api.game.get' ), array('id' => $gameId ) );
		return Decoder::parse( $this->get($url, $this->header, $this->body, $this->options), $error);
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
	public function getData( $response ) {
		if( ! isset($response) ) {
			return false;
		}
		if( ! isset($response['result']['code']) || $response['result']['code'] != 200 ) {
			return false;
		}
		if( ! isset($response['content']) ) {
			return false;
		}
		$content = self::json_decode( $response['content'] );
		if( ! isset($content->items ) ) {
			return false;
		}
		return $content->items;
	}
}
