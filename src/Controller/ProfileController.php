<?php

namespace App\Controller;

use App\Services\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/{example}', name: 'app_profile', requirements: ['example' => '\d+'])]
    public function index(Picture $picture, int $example = 1): Response
    {
        return $this->render('profile.html.twig', [
            'example' => $example,
            'upload_route' => $this->getUploadRouteFromExample($example),
            'profile_picture' => $picture->getPicture($example),
        ]);
    }

    private function getUploadRouteFromExample(int $example): string
    {
        return match ($example) {
            2 => 'app_upload_2',
            3 => 'app_upload_3',
            4 => 'app_upload_4',
            5 => 'app_upload_5',
            6 => 'app_upload_6',
            default => 'app_upload_1',
        };
    }
}
