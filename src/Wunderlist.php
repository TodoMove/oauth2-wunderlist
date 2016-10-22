<?php
namespace TodoMove\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Wunderlist extends AbstractProvider
{
    public function getBaseAuthorizationUrl()
    {
        return 'https://www.wunderlist.com/oauth/authorize';
    }


    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://www.wunderlist.com/oauth/access_token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://a.wunderlist.com/api/v1/use';
    }

    protected function getDefaultScopes()
    {
        return [];
    }

    protected function getAuthorizationHeaders($token = null) {
        return [
            'X-Access-Token' => $token,
            'X-Client-ID' => $this->clientId,
        ];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                $data['error'],
                isset($data['code']) ? (int) $data['code'] : $response->getStatusCode(),
                $response
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        $user = new WunderlistUser($response);

        return $user;
    }

}