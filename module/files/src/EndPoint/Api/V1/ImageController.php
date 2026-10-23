<?php

declare(strict_types=1);

namespace Files\EndPoint\Api\V1;

use Files\Application\Command\UploadFiles\Command as UploadFilesCommand;
use Files\Application\Command\UploadFiles\ImageDto;
use Files\Application\Query\GetByImageId\Query as GetByImageIdQuery;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ImageController extends AbstractController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface   $queryBus,
        private ValidationService   $validator
    ) {
    }

    #[Route('/upload', name: 'upload_file', methods: 'post')]
    public function list(Request $request): JsonResponse
    {
        $productId = $request->request->getString("productId");
        $images = $request->request->all("images");

        $prepareImages = [];
        foreach ($images as $image) {
            $prepareImages[] = new ImageDto(
                image: $image['image'] ?? '',
                type: $image['type'] ?? '',
                size: $image['size'] ?? ''
            );
        }

        $command = new UploadFilesCommand(
            productId: $productId,
            images: $prepareImages
        );

        $errors = $this->validator->validate($command);
        if (is_array($errors)) {
            return $this->json(data: ['error' => $errors], status: 400);
        }

        return $this->json($this->commandBus->dispatch($command));
    }

    #[Route('/get/{imageId}', name: 'get_file', methods: 'GET')]
    public function get(string $imageId): Response
    {
        $base64String = $this->queryBus->dispatch(new GetByImageIdQuery(
            imagesId: [$imageId]
        ));

        $image = array_shift($base64String);

        $response = new Response(
            content: $image['image'] ?? null
        );
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $imageId);

        return $response;
    }
}
