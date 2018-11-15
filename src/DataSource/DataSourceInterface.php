<?php

namespace App\DataSource;

interface DataSourceInterface
{
    /**
     * Create data source object
     *
     * @param string $data Extra data from -e option
     */
    public function __construct(string $data);

    /**
     * Count the number of objects in data source
     *
     * @return int
     */
    public function count(): int;
}
