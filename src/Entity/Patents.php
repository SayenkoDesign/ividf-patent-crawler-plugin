<?php
namespace IVIDF\Entity;

use IVIDF\Crawler\Crawler;
use IVIDF\Cache\Cache;
use wpdb;

class Patents
{
    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var wpdb
     */
    protected $wpdb;

    /**
     * @param Crawler $crawler
     * @param Cache $cache
     * @param wpdb $wpdb
     */
    public function __construct(Crawler $crawler, Cache $cache, wpdb $wpdb)
    {
        $this->crawler = $crawler;
        $this->cache = $cache;
        $this->wpdb = $wpdb;
        $this->getAll();
    }

    /**
     * get all the patents.
     * If the cache is not fresh update it
     *
     * @return array|null|object
     */
    public function getAll()
    {
        $table = $this->wpdb->prefix . "ividf_patents";
        if(!$this->cache->isFresh()) {
            $data = $this->crawler->getAll();
            foreach($data->data as $patent) {
                $applied = $patent->applied_on ? \DateTime::createFromFormat('d M Y', $patent->applied_on)->format('Y-m-d') : null;
                $issued = $patent->issued_on ? \DateTime::createFromFormat('d M Y', $patent->issued_on)->format('Y-m-d') : null;
                $this->wpdb->replace($table, [
                    'patent_id' => $patent->patent_id,
                    'patent_url' => $patent->patent_url,
                    'inventors' => $patent->inventors,
                    'title' => $patent->title,
                    'status' => $patent->status,
                    'applied_on' => $applied,
                    'issued_on' => $issued,
                ]);
            }
            $this->cache->updated();
        }

        $result = $this->wpdb->get_results("SELECT * FROM $table WHERE 1");
        return $result;
    }
}