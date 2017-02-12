<?php

namespace App\Services;


use AWS;
use Aws\S3\Exception\S3Exception;

class S3Service
{

    /**
     * @var \Aws\S3\S3Client
     */
    private $s3;

    /**
     * @var string
     */
    private $bucket;


    public function __construct()
    {
        $this->s3               = AWS::createClient('s3');
        $this->bucket           = config('aws.bucket');
    }

    /**
     * @param   string      $key
     * @param   mixed       $payload
     * @param   bool        $firstTry
     * @return  string      $path       The full path to where the file is saved
     */
    public function store($key, $payload, $firstTry = true)
    {
        try
        {
            $response           = $this->s3->putObject(array(
                'Bucket'        => $this->bucket,
                'Key'           => $key,
                'Body'          => $payload,
            ));

            return $response['ObjectURL'];
        }
        catch (S3Exception $ex)
        {
            // It shouldn't fail, but it does every now and then. Try it again and then give up
            if ($firstTry)
                return $this->store($key, $payload, false);
            else
                throw $ex;
        }
    }

    /**
     * @param   string      $key
     * @param   bool $firstTry
     * @return  string
     */
    public function get($key, $firstTry = true)
    {
        $request = [
            'Bucket'            => $this->bucket,
            'Key'               => $key
        ];
        
        try 
        {
            $response           = $this->s3->getObject($request);
            $body               = $response['Body'];
            return (string)$body;
        }
        catch (S3Exception $ex)
        {
            // It shouldn't fail, but it does every now and then. Try it again and then give up
            if ($firstTry)
                return $this->get($key, false);
            else
                throw new $ex;
        }
    }

    /**
     * @param   string      $key
     * @param   bool $firstTry
     * @return  bool
     */
    public function delete($key, $firstTry = true)
    {
        $request = [
            'Bucket'            => $this->bucket,
            'Key'               => $key
        ];

        try
        {
            $this->s3->deleteObject($request);
            return true;
        }
        catch (S3Exception $ex)
        {
            // It shouldn't fail, but it does every now and then. Try it again and then give up
            if ($firstTry)
                return $this->delete($key, false);
            else
                throw $ex;
        }
    }
    
}