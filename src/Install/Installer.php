<?php
namespace IVIDF\Install;

use wpdb;

class Installer
{
    /**
     * @var
     */
    private $wpdb;

    /**
     * @var callable
     */
    private $dbDelta;

    /**
     * @param wpdb $wpdb
     * @param callable $dbdelta
     */
    public function __construct(wpdb $wpdb, Callable $dbdelta)
    {
        $this->wpdb = $wpdb;
        $this->dbDelta = $dbdelta;
    }

    /**
     * install database schemas
     */
    public function installSchema()
    {
        $patents = $this->buildPatentTableSchema();
        call_user_func_array($this->dbDelta, [$patents]);
        $updated = $this->buildLastUpdatedTableSchema();
        call_user_func_array($this->dbDelta, [$updated]);
    }

    /**
     * build schema for patent table
     * @return string
     */
    protected function buildPatentTableSchema()
    {
        $table = $this->wpdb->prefix . "ividf_patents";
        $charset = $this->wpdb->get_charset_collate();

        return "CREATE TABLE $table (
            patent_id CHAR(32) NOT NULL,
            patent_url CHAR(32) NOT NULL,
            inventors VARCHAR(64) NULL,
            title VARCHAR(255) NOT NULL,
            status VARCHAR(16) NOT NULL,
            applied_on DATE NOT NULL,
            issued_on DATE NULL,
            PRIMARY KEY  (patent_id)
        ) $charset;";
    }

    /**
     * build schema for last updated table
     * @return string
     */
    protected function buildLastUpdatedTableSchema()
    {
        $table = $this->wpdb->prefix . "ividf_updated";
        $charset = $this->wpdb->get_charset_collate();

        return "CREATE TABLE $table (
            last_updated TIMESTAMP NOT NULL,
            PRIMARY KEY  (last_updated)
        ) $charset;";
    }
}