<?php

namespace ConditionTests;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client as HTTPClient;
use PHPUnit\Framework\TestCase;

final class EndpointTest extends TestCase
{
    private const API_ENDPOINT = 'https://simple-api-local-dev.lndo.site/example/';

    public function testHomepageEndpoint(): void
    {
        $client = new HTTPClient();
        $response = $client->request('GET', self::API_ENDPOINT);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test404EndpointDoesNotExist(): void
    {
        $client = new HTTPClient();
        try {
            $response = $client->request('GET', self::API_ENDPOINT . 'someerror/');
        } catch (ClientException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }
    }
}
