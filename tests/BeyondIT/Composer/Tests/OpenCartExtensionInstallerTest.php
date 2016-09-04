<?php

namespace BeyondIT\Composer\Tests;

use BeyondIT\Composer\OpenCartExtensionInstaller;
use Composer\Composer;
use Composer\Config;
use Composer\Package\Package;
use Composer\Package\RootPackage;

class OpenCartExtensionInstallerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OpenCartExtensionInstaller
     */
    protected $openCartExtensionInstaller;

    public function setUp()
    {
        $composer = new Composer();
        $package = new RootPackage("test","1","1");
        $package->setExtra([
            'opencart-dir' => 'tests/resources/sampleocdir'
        ]);

        $composer->setPackage($package);
        $composer->setConfig(new Config());

        $this->openCartExtensionInstaller = new OpenCartExtensionInstaller(
            $this->getMockBuilder('Composer\IO\IOInterface')->getMock() ,
            $composer
        );
    }

    public function testPhpOCInstaller()
    {
        $this->openCartExtensionInstaller->runPhpExtensionInstaller("tests/resources/sampleinstaller/installer.php");
        $this->assertTrue($_ENV['installer_called']);
    }

    public function testXmlOCInstaller()
    {
        $this->openCartExtensionInstaller->runXmlExtensionInstaller("tests/resources/sampleinstaller/installer.xml","test/a-b-c");
        $this->assertTrue(is_file('tests/resources/sampleocdir/system/test_a_b_c.ocmod.xml'));
        unlink('tests/resources/sampleocdir/system/test_a_b_c.ocmod.xml');
    }

    public function testRetrievingOCDir()
    {
        $this->assertEquals("tests/resources/sampleocdir",$this->openCartExtensionInstaller->getOpenCartDir());
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