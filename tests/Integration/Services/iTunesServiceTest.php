<?php

namespace Tests\Integration\Services;

use App\Services\iTunesService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Cache\Repository as Cache;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class iTunesServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetTrackUrl()
    {
        $term = 'Foo Bar';

        /** @var Client $client */
        $client = Mockery::mock(Client::class, [
            'get' => new Response(200, [], file_get_contents(__DIR__.'../../../blobs/itunes/track.json')),
        ]);

        /** @var Cache|MockInterface $cache */
        $cache = app(Cache::class);

        $url = (new iTunesService($client, $cache))->getTrackUrl($term);

        self::assertEquals(
            'https://itunes.apple.com/us/album/i-remember-you/id265611220?i=265611396&uo=4&at=1000lsGu',
            $url
        );

        self::assertNotNull(cache('b57a14784d80c58a856e0df34ff0c8e2'));
    }
}
