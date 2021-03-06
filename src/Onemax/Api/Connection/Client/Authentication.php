<?php
namespace Onemax\Api\Connection\Client;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Onemax\Api\Connection\Client\ClientBase;
class Authentication extends ClientBase
{
    public function __construct( $configs )
    {
        parent::__construct( $configs );
        
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);
        $defaults = array(
            'consumer_key'     => $this->configs['consumer_key'],
            'consumer_secret'  => $this->configs['consumer_secret'],
            'token_secret'     => '',
            'token'            => '',
            'request_method'   => Oauth1::REQUEST_METHOD_QUERY,
            'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC
        );
        $configs = array_merge($defaults, $this->configs);
        $middleware = new Oauth1( $configs);
        $stack->push($middleware);

        $this->client = new Client([
            'base_uri' => $this->configs['base_url'],
            'handler'  => $stack,
            'auth'     => 'oauth',
        ]);
        
    }
}
