<?php
/**
 * OAuth 2.0 Password grant.
 *
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace ODMAuth\Grant;

use DateInterval;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use phpseclib\Crypt\RSA;
use Test\Repositories\ExamRepository;
use Test\Services\DoExamServiceInterface;

/**
 * Password grant class.
 */
class DoExamGrant extends AbstractGrant
{

    /**
     * @var ExamRepository
     */
    protected $examRepository;

    /**
     * @var DoExamServiceInterface
     */
    protected $doExamService;

    /**
     * @param ExamRepository         $examRepository
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     */
    public function __construct(
        ExamRepository $examRepository,
        DoExamServiceInterface $doExamService,
        RefreshTokenRepositoryInterface $refreshTokenRepository
    ) {
        $this->examRepository = $examRepository;
        $this->doExamService = $doExamService;
        $this->setRefreshTokenRepository($refreshTokenRepository);

        $this->refreshTokenTTL = new DateInterval('P1M');
    }

    /**
     * {@inheritdoc}
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ) {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens        
        $accessToken = $this->issueAccessToken(new DateInterval('PT30S'), $client, $user->getIdentifier(), $finalizedScopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        // Send events to emitter
        $this->getEmitter()->emit(new RequestEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request));
        $this->getEmitter()->emit(new RequestEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request));

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ClientEntityInterface  $client
     *
     * @throws OAuthServerException
     *
     * @return UserEntityInterface
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $pin = $this->getRequestParameter('PIN', $request);
        if (is_null($pin)) {
            throw OAuthServerException::invalidRequest('PIN');
        }
        $user = null;
        
        $examDocument = $this->examRepository->getExamInfo($pin);
        if($examDocument && $this->doExamService->isAllowAccessExam($examDocument)) {
            $candidates = $examDocument->getCandidates();
            $candidate = $candidates[0];
            if ($candidate->getIsPinValid()) {
                $user = new \Zend\Expressive\Authentication\OAuth2\Entity\UserEntity('pin_=###@@###'.$examDocument->getId().'###@@###'.$candidate->getId());
            }
        }

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'pin';
    }
}
