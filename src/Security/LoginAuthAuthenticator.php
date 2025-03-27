<?php
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use App\Entity\User;

use App\Repository\UserRepository;

class LoginAuthAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private JWTTokenManagerInterface $jwtManager,

        private UserRepository $userRepository
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/api/connexion' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
{
    $data = json_decode($request->getContent(), true);

    return new Passport(
        new UserBadge($data['email'], function($userIdentifier) {
            return $this->userRepository->findOneBy(['email' => $userIdentifier]);
        }),
        new PasswordCredentials($data['password'])
    );
}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();

        $jwt = $this->jwtManager->create($user);

        return new JsonResponse([
            'message' => 'Connexion réussie',
            'token' => $jwt,
            'role' => in_array('ROLE_ADMIN', $user->getRoles()) ? 'admin' : 'user',
            'user' => [
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'message' => 'Identifiants invalides'
        ], 401);
    }
}
