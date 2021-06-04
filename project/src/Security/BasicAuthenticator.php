<?php

namespace App\Security;

use App\Model\User\Service\PasswordHasher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class BasicAuthenticator extends AbstractGuardAuthenticator
{
    public function __construct(private PasswordHasher $passwordHasher)
    {
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('PHP_AUTH_USER');
    }

    /**
     * @return array{username: string, password: string}
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->headers->get('PHP_AUTH_USER') ?: '',
            'password' => $request->headers->get('PHP_AUTH_PW') ?: ''
        ];
    }

    /**
     * @param array{username: string, password: string} $credentials
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        try {
            $user = $userProvider->loadUserByUsername($credentials['username']);
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException($e->getMessage());
        }
        return $user;
    }

    /**
     * @param array{username: string, password: string} $credentials
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordHasher->validate($credentials['password'], $user->getPassword() ?: '');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = ['message' => strtr($exception->getMessageKey(), $exception->getMessageData())];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse(['message' => 'Authentication Required'], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
