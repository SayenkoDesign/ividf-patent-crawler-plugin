<?php
namespace IVIDF\Cache;

use wpdb;

class Cache
{
    /**
     * @var wpdb
     */
    protected $wpdb;

    /**
     * @param wpdb $wpdb
     */
    public function __construct(wpdb $wpdb)
    {
        $this->wpdb = $wpdb;
    }

    /**
     * check if the cache is still fresh
     * @return bool
     */
    public function isFresh()
    {
        $table = $this->wpdb->prefix . 'ividf_updated';
        $result = $this->wpdb->query("SELECT last_updated
            FROM $table
            WHERE last_updated > NOW() - INTERVAL 1 DAY
            ORDER BY last_updated
            DESC LIMIT 1
        ");
        return (bool) $result;
    }

    /**
     * mark the cache as updated
     */
    public function updated()
    {
        $table = $this->wpdb->prefix . 'ividf_updated';
        $this->wpdb->insert($table, ['last_updated' => current_time('mysql')]);
    }
}