<?php

namespace App\Services\OAuth;


use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Exception\AccessDeniedException;
use League\OAuth2\Server\Exception\InvalidClientException;
use League\OAuth2\Server\Exception\InvalidCredentialsException;
use League\OAuth2\Server\Exception\InvalidRequestException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Event;
use Auth;
use EntityManager;
use League\OAuth2\Server\Util\SecureKey;

class StaffBarCodeGrant extends AbstractGrant
{

    /**
     * Grant identifier
     *
     * @var string
     */
    protected $identifier = 'barCode';

    /**
     * Response type
     *
     * @var string
     */
    protected $responseType;

    /**
     * Callback to authenticate a user's name and password
     *
     * @var callable
     */
    protected $callback;

    /**
     * Access token expires in override
     *
     * @var int
     */
    protected $accessTokenTTL;

    /**
     * Set the callback to verify a user's username and password
     *
     * @param callable $callback The callback function
     *
     * @return void
     */
    public function setVerifyCredentialsCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Return the callback function
     *
     * @return callable
     *
     * @throws
     */
    protected function getVerifyCredentialsCallback()
    {
        return $this->callback;
    }

    function verify ($barCode)
    {
        $staffRepo                  = EntityManager::getRepository('App\Models\CMS\Staff');
        $staff                      = $staffRepo->getOneByBarCode($barCode);

        if (is_null($staff))
            return false;

        Auth::setUser($staff);
        return $staff->getId();
    }

    public function completeFlow()
    {
        // Get the required params
        $clientId = $this->server->getRequest()->request->get('client_id', $this->server->getRequest()->getUser());
        if (is_null($clientId)) {
            throw new InvalidRequestException('client_id', ['shortName' => 'oauthMissingClientIdApiKey']);
        }

        $clientSecret = $this->server->getRequest()->request->get('client_secret',
            $this->server->getRequest()->getPassword());
        if (is_null($clientSecret)) {
            throw new InvalidRequestException('client_secret', ['shortName' => 'oauthMissingClientSecretApiKey']);
        }

        // Validate client ID and client secret
        $client = $this->server->getClientStorage()->get(
            $clientId,
            $clientSecret,
            null,
            $this->getIdentifier()
        );

        if (($client instanceof ClientEntity) === false) {
            $this->server->getEventEmitter()->emit(new Event\ClientAuthenticationFailedEvent($this->server->getRequest()));
            throw new InvalidClientException();
        }

        $barCode = $this->server->getRequest()->request->get('barCode',
            $this->server->getRequest()->getPassword());
        if (is_null($barCode)) {
            throw new InvalidRequestException('barCode', ['shortName' => 'oauthMissingBarCode']);
        }

        $userId = call_user_func($this->getVerifyCredentialsCallback(), $barCode);

        if ($userId === false) {
            $this->server->getEventEmitter()->emit(new Event\UserAuthenticationFailedEvent($this->server->getRequest()));
            throw new InvalidCredentialsException();
        }

        // Validate any scopes that are in the request
        $scopeParam = $this->server->getRequest()->request->get('scope', '');
        $scopes = $this->validateScopes($scopeParam, $client);

        // Create a new session
        $session = new SessionEntity($this->server);
        $session->setOwner('user', $userId);
        $session->associateClient($client);

        //if client has requested non default ttl set it here
        $expires = $this->server->getRequest()->request->get('access_token_ttl', null);
        if(!is_null($expires)){
            $this->setAccessTokenTTL($expires);
        }

        // Generate an access token
        $accessToken = new AccessTokenEntity($this->server);
        $accessToken->setId(SecureKey::generate());
        $accessToken->setExpireTime($this->getAccessTokenTTL() + time());

        // Associate scopes with the session and access token
        foreach ($scopes as $scope) {
            $session->associateScope($scope);
        }

        foreach ($session->getScopes() as $scope) {
            $accessToken->associateScope($scope);
        }

        $this->server->getTokenType()->setSession($session);
        $this->server->getTokenType()->setParam('access_token', $accessToken->getId());
        $this->server->getTokenType()->setParam('expires_in', $this->getAccessTokenTTL());

        // Associate a refresh token if set
        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken = new RefreshTokenEntity($this->server);
            $refreshToken->setId(SecureKey::generate());
            $refreshToken->setExpireTime($this->server->getGrantType('refresh_token')->getRefreshTokenTTL() + time());
            $this->server->getTokenType()->setParam('refresh_token', $refreshToken->getId());
        }

        // Save everything
        $session->save();
        $accessToken->setSession($session);
        $accessToken->save();

        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken->setAccessToken($accessToken);
            $refreshToken->save();
        }

        return $this->server->getTokenType()->generateResponse();
    }
}