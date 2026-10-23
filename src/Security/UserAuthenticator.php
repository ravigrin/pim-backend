<?php

declare(strict_types=1);

namespace App\Security;

use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class UserAuthenticator extends AbstractAuthenticator
{
    /**
     * @var string
     */
    protected const HEADER_AUTH_TOKEN = 'X-AUTH-TOKEN';

    /**
     * @var string[]
     */
    protected const LOGIN_PATH = ['/api/v2/login', '/api/v2/file/template_pim.xlsx', '/api/v2/file/upload'];

    public function supports(Request $request): ?bool
    {
        foreach (self::LOGIN_PATH as $path) {
            if (is_int(strripos($request->getPathInfo(), $path))) {
                return false;
            }
        }
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get(self::HEADER_AUTH_TOKEN);

        if ($apiToken === null) {
            throw new CustomUserMessageAuthenticationException('auth token not found.');
        }

        return new SelfValidatingPassport(new UserBadge($apiToken));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }
}
