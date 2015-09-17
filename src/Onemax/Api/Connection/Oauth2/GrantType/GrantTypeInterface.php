<?php
namespace Onemax\Api\Connection\Oauth2\GrantType;

use Onemax\Api\Connection\Oauth2\AccessToken;

interface GrantTypeInterface
{
    /**
     * Get the token data returned by the OAuth2 server.
     *
     * @return AccessToken
     */
    public function getToken();
}
