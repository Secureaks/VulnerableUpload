<?php

namespace App\Controller;

use App\Services\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UploadController extends AbstractController
{
    /**
     * Vulnerable example 1: No protection at all
     *
     * The file is uploaded to the public directory without any protection.
     */
    #[Route('/upload/1', name: 'app_upload_1', methods: ['POST'])]
    public function example1(Request $request, Picture $picture): Response
    {
        $file = $request->files->get('file');

        $file->move($picture->getUploadDir(1), $file->getClientOriginalName());

        $picture->updatePicture(1, $file->getClientOriginalName());

        return $this->redirectToRoute('app_profile', ['example' => 1]);
    }

    /**
     * Vulnerable example 2: Content-Type check from request
     *
     * A check of the Content-Type parameter from the request is performed.
     */
    #[Route('/upload/2', name: 'app_upload_2', methods: ['POST'])]
    public function example2(Request $request, Picture $picture): Response
    {
        $file = $request->files->get('file');

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($file->getClientMimeType(), $allowedMimeTypes)) {
            $this->addFlash('error', 'Only images are allowed');
            return $this->redirectToRoute('app_profile', ['example' => 2]);
        }

        $file->move($picture->getUploadDir(2), $file->getClientOriginalName());

        $picture->updatePicture(2, $file->getClientOriginalName());

        return $this->redirectToRoute('app_profile', ['example' => 2]);
    }

    /**
     * Vulnerable example 3: Content-Type check from file
     *
     * A check of the Content-Type parameter from the file is performed.
     */
    #[Route('/upload/3', name: 'app_upload_3', methods: ['POST'])]
    public function example3(Request $request, Picture $picture): Response
    {
        $file = $request->files->get('file');

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            $this->addFlash('error', 'Only images are allowed');
            return $this->redirectToRoute('app_profile', ['example' => 3]);
        }

        $file->move($picture->getUploadDir(3), $file->getClientOriginalName());

        $picture->updatePicture(3, $file->getClientOriginalName());

        return $this->redirectToRoute('app_profile', ['example' => 3]);
    }

    /**
     * Vulnerable example 4: Weak extension check
     *
     * Check that the file extension is present in the path.
     */
    #[Route('/upload/4', name: 'app_upload_4', methods: ['POST'])]
    public function example4(Request $request, Picture $picture): Response
    {
        $file = $request->files->get('file');

        $allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif'];

        // Get the elements after the first dot
        $extension = substr($file->getClientOriginalName(), strpos($file->getClientOriginalName(), '.'));

        // Check if the file name contains any of the allowed extensions
        $validExtension = false;
        foreach ($allowedExtensions as $allowedExtension) {
            if (stripos($extension, $allowedExtension) !== false) {
                $validExtension = true;
                break;
            }
        }

        if (!$validExtension) {
            $this->addFlash('error', 'Only images are allowed');
            return $this->redirectToRoute('app_profile', ['example' => 4]);
        }

        $file->move($picture->getUploadDir(4), $file->getClientOriginalName());

        $picture->updatePicture(4, $file->getClientOriginalName());

        return $this->redirectToRoute('app_profile', ['example' => 4]);
    }

    /**
     * Vulnerable example 4: Blacklist bypass with .htaccess
     *
     * Check that the file extension is not PHP, but it can be bypassed with an .htaccess file.
     */
    #[Route('/upload/5', name: 'app_upload_5', methods: ['POST'])]
    public function example5(Request $request, Picture $picture): Response
    {
        $file = $request->files->get('file');

        $forbiddenExtensions = ['php', 'php3', 'php4', 'php5', 'phtml', 'html', 'htm', 'js'];

        if (in_array(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION), $forbiddenExtensions)) {
            $this->addFlash('error', 'The file extension is not allowed');
            return $this->redirectToRoute('app_profile', ['example' => 5]);
        }

        $file->move($picture->getUploadDir(5), $file->getClientOriginalName());

        $picture->updatePicture(5, $file->getClientOriginalName());

        return $this->redirectToRoute('app_profile', ['example' => 5]);
    }

    /** Secure example: File extension check with whitelist and size check
     *
     * Check that the file extension is present in the path and that the file size is less than 1MB.
     */
    #[Route('/upload/6', name: 'app_upload_6', methods: ['POST'])]
    public function example6(Request $request, Picture $picture): Response
    {
        $file = $request->files->get('file');

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 1024 * 1024; // 1MB

        if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
            $this->addFlash('error', 'Only images are allowed');
            return $this->redirectToRoute('app_profile', ['example' => 6]);
        }

        if ($file->getSize() > $maxFileSize) {
            $this->addFlash('error', 'The file is too large');
            return $this->redirectToRoute('app_profile', ['example' => 6]);
        }

        $randomFilename = bin2hex(random_bytes(16)) . '.' . $file->getClientOriginalExtension();

        $file->move($picture->getUploadDir(6), $randomFilename);

        $picture->updatePicture(6, $randomFilename);

        return $this->redirectToRoute('app_profile', ['example' => 6]);
    }

}
