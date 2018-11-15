<?php

namespace App\DataSource;

use App\Exceptions\InvalidFilePathException;

class FileFactory implements DataSourceFactoryInterface
{
    /**
     * Create file data source
     *
     * @param string $filePath File path from -e option
     * @throws InvalidFilePathException
     * @return DataSourceInterface
     */
    public function build(string $filePath): DataSourceInterface
    {
        if (empty($filePath)) {
            throw new InvalidFilePathException('file path is not provided');
        }

        return new File($filePath);
    }
}
