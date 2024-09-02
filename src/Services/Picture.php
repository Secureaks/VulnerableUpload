<?php

namespace App\Services;

use App\Entity\Picture as PictureEntity;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;

class Picture
{
    private string $uploadDir = __DIR__ . '/../../public/uploads';

    public function __construct(
        private readonly PictureRepository $pictureRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function updatePicture(int $example, string $path): void
    {
        $picture = $this->pictureRepository->getFirstImage($example);

        if ($picture) {
            $picture->setPath($path);
        } else {
            $picture = new PictureEntity();
            $picture->setExample($example);
            $picture->setPath($path);
            $this->entityManager->persist($picture);
        }

        $this->entityManager->flush();
    }

    public function getPicture(int $example): string
    {
        $picture = $this->pictureRepository->getFirstImage($example);
        return $picture ? 'uploads/' . $example . '/' . $picture->getPath() : 'images/profile.jpg';
    }

    public function getUploadDir(int $example): string
    {
        $fullDir = $this->uploadDir . '/' . $example;
        $this->createDirectoryIfNotExists($fullDir);
        return $fullDir;
    }

    private function createDirectoryIfNotExists(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}