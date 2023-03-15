<?php

namespace App\Services;

use ImageKit\ImageKit;

class ImageKitService
{
    protected $imageKit;

    public function __construct()
    {
        $this->imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT')
        );
    }

    public function upload($file, $fileName, $options = [])
    {
        $fileToBase64 =  base64_encode(file_get_contents($file));
        $uploadFile = $this->imageKit->uploadFile([
            'file' => $fileToBase64,
            'fileName' => $fileName,
            'options' => $options,
        ]);

        $reponse  = (array) $uploadFile;

        return (array) $reponse['result'];
    }

    public function get($fileId)
    {
        return $this->imageKit->getFileDetails($fileId);
    }

    public function delete($fileId)
    {
        return $this->imageKit->deleteFile($fileId);
    }
}