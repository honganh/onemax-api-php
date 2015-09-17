<?php
namespace Onemax\Api\Connection\Client;
use GuzzleHttp\Client;
use Onemax\Api\Connection\Oauth2\GrantType\RefreshToken;
use Onemax\Api\Connection\Oauth2\GrantType\PasswordCredentials;
use Onemax\Api\Connection\Oauth2\Oauth2Subscriber;
use Onemax\Api\Connection\Client\ClientBase;
class ClientCredential extends ClientBase
{
	public function __construct( $configs ) {
		parent::__construct( $configs );
		$oauth2Client = new Client(['base_url' => $this->configs['base_url'] ]);
		$config = [
		    'username'  => 'test@example.com',
		    'password'  => 'test password',
		    'client_id' => 'test-client',
		    'scope'     => 'administration',
		];

		$token = new PasswordCredentials($oauth2Client, $config);
		$refreshToken = new RefreshToken($oauth2Client, $config);

		$oauth2 = new Oauth2Subscriber($token, $refreshToken);

		$client = new Client([
		    'defaults' => [
		        'auth' => 'oauth2',
		        'subscribers' => [$oauth2],
		    ],
		]);
	}
}