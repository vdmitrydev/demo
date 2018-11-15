<?php

namespace App\DataSource;

use App\Exceptions\FileNotFoundException;
use App\Exceptions\InvalidFileException;

class File implements DataSourceInterface
{
    /**
     * File path
     *
     * @var string
     */
    private $path;

    /**
     * Create file object
     *
     * @throws FileNotFoundException
     * @param string $path
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException('file not found');
        }

        $this->path = $path;
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Count the number of lines in file
     *
     * @throws InvalidFileException
     * @return int
     */
    public function count(): int
    {
        // If file is not readable we will throw exception
        // So the warning can be muted
        $handle = @fopen($this->path, 'rb');
        $count = 0;

        if ($handle) {
            while (false !== fgets($handle)) {
                $count++;
            }
            fclose($handle);
        } else {
            throw new InvalidFileException('file cannot be opened');
        }

        return $count;
    }
}
