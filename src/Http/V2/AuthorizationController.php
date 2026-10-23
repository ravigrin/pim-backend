<?php

declare(strict_types=1);

namespace App\Http\V2;

use Pim\Application\Query\User\Login\Query as UserLoginQuery;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
final class AuthorizationController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    #[Route('/login', name: 'api_login', methods: ['post'])]
    public function login(Request $request): Response
    {
        $username = $request->request->getString('username');
        $password = $request->request->getString('password');

        if ($username && $password) {
            $user = (array)$this->queryBus->dispatch(
                new UserLoginQuery($username, $password)
            );

            if ($user !== []) {
                return $this->json(
                    data: $user,
                    status: Response::HTTP_ACCEPTED
                );
            }
        }

        return $this->json([
            'message' => 'wrong credentials',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
