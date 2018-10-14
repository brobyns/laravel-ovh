<?php
/**
 * Created by PhpStorm.
 * User: bramr
 * Date: 14/10/2018
 * Time: 13:00
 */

namespace Sausin\LaravelOvh;


class OVHContainer
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Generate a signature and get data for form POST middleware request
     *
     * @param  int $maxFileSize
     * @param  int $maxFileCount
     * @param  int $expiration
     * @param string $path
     * @param  string $redirect
     * @return array
     */
    public function getFormPostMiddlewareData($maxFileSize, $maxFileCount, $expiration, $path = '', $redirect = '')
    {

        // expiry is relative to current time
        $expiresAt = $expiration instanceof Carbon ? $expiration->timestamp : (int) (time() + 60 * 60);

        // the url on the OVH host
        $codePath = sprintf(
            '/v1/AUTH_%s/%s/%s',
            $this->config['projectId'],
            $this->config['container'],
            $path
        );

        // body for the HMAC hash
        $body = sprintf("%s\n%s\n%s\n%s\n%s", $codePath, $redirect,
            $maxFileSize, $maxFileCount, $expiresAt);

        // the actual hash signature
        $signature = hash_hmac('sha1', $body, $this->config['urlKey']);

        return [
            'url' => sprintf('https://storage.%s.cloud.ovh.net%s', $this->config['region'], $codePath),
            'signature' => $signature,
            'expires' => $expiresAt,
            'max_file_size' => $maxFileSize,
            'max_file_count' => $maxFileCount
        ];
    }


}