<?php

namespace App\DataSource;

interface DataSourceFactoryInterface
{
    /**
     * Create data source object
     *
     * @param string $data Extra data from -e option
     * @return DataSourceInterface
     */
    public function build(string $data): DataSourceInterface;
}
