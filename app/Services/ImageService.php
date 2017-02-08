<?php

namespace App\Services;


use App\Models\Support\Image;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image AS Intervention;

class ImageService
{

    /**
     * @var S3Service
     */
    protected $s3Service;


    public function __construct()
    {
        $this->s3Service            = new S3Service();
    }

    /**
     * @param   $filePath           string
     * @param   $fileName           string|null
     * @return  Image
     */
    public function handleImage($filePath, $fileName = null)
    {
        $image                          = new Image();

        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            $explodedUrl                = explode('/', $filePath);
            //  Get the last element in the array
            $fileName                   = $explodedUrl[sizeof($explodedUrl) - 1];
            //  Strip any spaces
            $fileName                   = str_replace(' ', '', $fileName);
            //  Strip off the query string, if set
            $fileName                   = strtok($fileName, '?');
            //  Strip off the query string for the filePath, if set
            $filePath                   = strtok($filePath, '?');
            /**
             * We need to fetch the file URL and download it to a temporary local location
             */
            $contents                   = file_get_contents($filePath);
            $filePath                   = storage_path('app') . '/' . Str::random(50);
            file_put_contents($filePath, $contents);
        }

        if (strlen($fileName) > 50)
            $fileName                   = Str::random(50);
        if (!str_contains($fileName, '.'))
            $fileName                   .= '.';

        $fileName                       = strstr($fileName, '.', true) . '.png';

        try
        {
            //  dd($filePath);
            /**
             * Use a standard of PNG encoding
             */
            $originalImage              = Intervention::make($filePath)->encode('png');

            $originalImage->save();

            $originalKey                = Str::random(50) . $fileName;
            $s3Path                     = $this->s3Service->store($originalKey, (string)$originalImage);
            $image->setPath($s3Path);

            unlink($filePath);
            unset($originalImage);
            unset($resizedImage);
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }

        return $image;
    }

}