<?php

declare(strict_types=1);

namespace App\Http\V2;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class SourceController extends AbstractController
{
    #[Route('/sources', name: 'sources_list', methods: 'post')]
    #[IsGranted('ROLE_SOURCE_SHOW', message: 'no rights to watch user')]
    public function list(): JsonResponse
    {
        return $this->json(['pim', 'ozon', 'wildberries']);
    }
}
