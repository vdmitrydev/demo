<?php

use App\DataSource\FileFactory;
use App\DataSource\File;
use App\Exceptions\InvalidFilePathException;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class FileFactoryTest extends TestCase
{
    /** @var  \org\bovigo\vfs\vfsStreamDirectory */
    private $fileSystem;

    /** @var  FileFactory */
    private $fileFactory;

    public function setUp()
    {
        $directory = [
            'valid.txt' => "123"
        ];

        $this->fileSystem = vfsStream::setup('test', 444, $directory);

        $this->fileFactory = new FileFactory();
    }

    public function testBuildFailWithEmptyFilePath()
    {
        $this->expectException(InvalidFilePathException::class);
        $this->fileFactory->build('');
    }

    public function testBuild()
    {
        $filePath = $this->fileSystem->url() . '/valid.txt';
        $file = $this->fileFactory->build($filePath);
        $this->assertInstanceOf(File::class, $file);
        $this->assertSame($file->getPath(), $filePath);
    }
}
