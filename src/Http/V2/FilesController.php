<?php

declare(strict_types=1);

namespace App\Http\V2;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
final class FilesController extends AbstractController
{
    #[Route('/file/template_pim.xlsx', name: 'files', methods: 'get')]
    public function files(Request $request): BinaryFileResponse
    {
        return new BinaryFileResponse($_SERVER['PWD'] . '/files/templates/template_pim.xlsx');
    }
}
