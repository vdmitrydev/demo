<?php

use App\DataSource\File;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use App\Exceptions\FileNotFoundException;
use App\Exceptions\InvalidFileException;

class FileTest extends TestCase
{
    /** @var  \org\bovigo\vfs\vfsStreamDirectory */
    private $fileSystem;

    public function setUp()
    {
        $directory = [
            'valid.txt' => "1\n2\n3",
            'valid2.txt' => "1\r\n2\r\n3\r\n4",
            'secret.txt' => "1"
        ];

        $this->fileSystem = vfsStream::setup('test', 444, $directory);
        $this->fileSystem->getChild('secret.txt')->chmod(000);
    }

    public function testConstructorFailWithNonexistentFile()
    {
        $this->expectException(FileNotFoundException::class);
        new File($this->fileSystem->url() . '/wrong.txt');
    }

    public function testConstructorSavesFilePath()
    {
        $filePath = $this->fileSystem->url() . '/valid.txt';
        $file = new File($filePath);
        $this->assertSame($filePath, $file->getPath());
    }

    public function testCountFailWithUnreadableFile()
    {
        $this->expectException(InvalidFileException::class);
        $filePath = $this->fileSystem->url() . '/secret.txt';
        $file = new File($filePath);
        $file->count();
    }

    public function testCount()
    {
        $filePath = $this->fileSystem->url() . '/valid.txt';
        $file = new File($filePath);
        $this->assertSame(3, $file->count());

        $filePath = $this->fileSystem->url() . '/valid2.txt';
        $file = new File($filePath);
        $this->assertSame(4, $file->count());
    }
}