<?php

namespace ConditionTests;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client as HTTPClient;
use PHPUnit\Framework\TestCase;

final class ContentTypeTest extends TestCase
{
    private const API_ENDPOINT = 'https://simple-api-local-dev.lndo.site/example/';

    public function testContentTypeJson(): void
    {
        $client = new HTTPClient();
        $response = $client->request('GET', self::API_ENDPOINT);
        $this->assertEquals('{"success":["Awesome"]}', $response->getBody());
    }

    public function testContentTypeText(): void
    {
        $client = new HTTPClient();
        $response = $client->request('GET', self::API_ENDPOINT . '?content_type=text');
        $this->assertEquals('Awesome', $response->getBody());
    }

    public function testContentTypeHtml(): void
    {
        $client = new HTTPClient();
        $response = $client->request('GET', self::API_ENDPOINT . '/html?content_type=html');
        $this->assertEquals('<p>Awesome</p>', $response->getBody());
    }
}
