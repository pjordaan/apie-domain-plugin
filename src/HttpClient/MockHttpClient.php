<?php


namespace W2w\Lib\ApieDomainPlugin\HttpClient;

use Pdp\HttpClient;
use Pdp\HttpClientException;

class MockHttpClient implements HttpClient
{

    /**
     * Returns the content fetched from a given URL.
     *
     * @param string $url
     *
     * @return string Retrieved content
     * @throws HttpClientException If an errors occurs while fetching the content from a given URL
     *
     */
    public function getContent(string $url): string
    {
        return file_get_contents(__DIR__ . '/../../data/public-suffix.dat');
    }
}
