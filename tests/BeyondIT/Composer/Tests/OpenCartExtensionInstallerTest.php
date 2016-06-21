<?php

namespace BeyondIT\Composer\Tests;

use BeyondIT\Composer\OpenCartExtensionInstaller;
use Composer\Composer;
use Composer\Config;

class OpenCartExtensionInstallerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OpenCartExtensionInstaller
     */
    protected $openCartExtensionInstaller;

    public function setUp()
    {
        $composer = new Composer();
        $composer->setConfig(new Config());

        $this->openCartExtensionInstaller = new OpenCartExtensionInstaller(
            $this->getMockBuilder('Composer\IO\IOInterface')->getMock() ,
            $composer
        );
    }

    public function testFileCopying()
    {
        mkdir('tests/tocopy');

        $this->openCartExtensionInstaller->copyFiles('tests/resources','tests/tocopy', ['mappings' => ['sampledir/samplefile.txt']]);
        $this->assertTrue(is_file('tests/tocopy/sampledir/samplefile.txt'));

        unlink('tests/tocopy/sampledir/samplefile.txt');
        rmdir('tests/tocopy/sampledir');
        rmdir('tests/tocopy');
    }

    public function testSrcDir()
    {
        $srcDir = $this->openCartExtensionInstaller->getSrcDir('vendor/vendor-name/project', ['src-dir' => 'src/main/upload']);
        $this->assertEquals('vendor/vendor-name/project/src/main/upload', $srcDir);
    }

}