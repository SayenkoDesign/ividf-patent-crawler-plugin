<?php
namespace IVIDF\Crawler;

use GuzzleHttp\Client;
use IVIDF\Cache\Cache;

class Crawler
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Cache $cache
     * @param Client|null $client
     */
    public function __construct(Cache $cache, Client $client = null)
    {
        $this->cache = $cache;
        $this->client = $client ?: new Client();
    }

    /**
     * get total count of patents
     * @return mixed
     */
    public function getTotal()
    {
        $response = $this->client->post( 'https://idf.intven.com/public_patent_listing.json', [
            'verify' => false,
            'json'    => [
                "report_type" => "public_patent_listing",
                "queryFields" => new \stdClass(),
                "filters" => new \stdClass(),
                "per_page" => 0,
                "from" => 0,
                "sort" => "issued_on",
                "sort_order" => "desc"
            ],
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $data->meta_data->total_count;
    }

    /**
     * get all the patents
     * @return array|mixed|object
     */
    public function getAll()
    {
        $response = $this->client->post( 'https://idf.intven.com/public_patent_listing.json', [
            'verify' => false,
            'json'    => [
                "report_type" => "public_patent_listing",
                "queryFields" => new \stdClass(),
                "filters" => new \stdClass(),
                "per_page" => $this->getTotal(),
                "from" => 0,
                "sort" => "issued_on",
                "sort_order" => "desc"
            ],
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $data;
    }
}
?>
