<?php

namespace BeyondIT\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;
use BeyondIT\Composer\OpenCartNaivePhpInstaller;

class OpenCartExtensionInstaller extends LibraryInstaller
{
    public function getOpenCartDir()
    {
        $extra = $this->composer->getPackage()->getExtra();

        if (isset($extra['opencart-dir'])) {
            return $extra['opencart-dir'];
        }

        // OC 2.2.0.0 directory "upload" is root dir
        return 'upload';
    }

    /**
     * Get src path of module
     */
    public function getSrcDir($installPath, array $extra)
    {
        if (isset($extra['src-dir']) && is_string($extra['src-dir'])) {
            $installPath .= "/" . $extra['src-dir'];
        } else { // default
            $installPath .= "/src/upload";
        }

        return $installPath;
    }

    /**
     * @param array $extra extra array
     */
    public function copyFiles($sourceDir, $targetDir, array $extra)
    {
        $filesystem = new Filesystem();

        if (isset($extra['mappings']) && is_array($extra['mappings'])) {
            foreach($extra['mappings'] as $mapping) {
                $source = $sourceDir . "/" . $mapping;
                $target = $targetDir . "/" . $mapping;
                $filesystem->copy($source, $target, true);
            }
        }
    }

    /**
     * @param string $srcDir Src Directory of installed package
     * @param string $name Name of installed package
     * @param array $extra extra array
     */
    public function runExtensionInstaller($srcDir, $name, array $extra)
    {
        if (isset($extra['installers']) && is_array($extra['installers'])) {
            if (isset($extra['installers']['php'])) {
                $this->runPhpExtensionInstaller($srcDir ."/". $extra['installers']['php']);
            }
            if (isset($extra['installers']['xml'])) {
                $this->runXmlExtensionInstaller($srcDir ."/". $extra['installers']['xml'], $name);
            }
        }
    }

    public function runPhpExtensionInstaller($file) {
        $openCartAdmin = $this->getOpenCartDir() . "/admin/index.php";

        // opencart not yet available
        if (!is_file($openCartAdmin)) {
            return;
        }

        $_SERVER['SERVER_PORT'] = 80;
        $_SERVER['SERVER_PROTOCOL'] = 'CLI';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        ob_start();
        include($openCartAdmin);
        ob_end_clean();

        // $registry comes from admin/index.php
        OpenCartNaivePhpInstaller::$registry = $registry;

        $installer = new OpenCartNaivePhpInstaller();
        $installer->install($file);
    }

    public function runXmlExtensionInstaller($src, $name) {
        $name = strtolower(str_replace(array("/","-"),"_",$name));
        $filesystem = new Filesystem();
        $target = $this->getOpenCartDir() . "/system/" . $name . ".ocmod.xml";

        $filesystem->copy($src, $target, true);
    }

    /**
     * { @inheritDoc }
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        $srcDir = $this->getSrcDir($this->getInstallPath($package), $package->getExtra());
        $openCartDir = $this->getOpenCartDir();

        $this->copyFiles($srcDir, $openCartDir, $package->getExtra());
        $this->runExtensionInstaller($this->getInstallPath($package), $package->getName(), $package->getExtra());
    }

    /**
     * { @inheritDoc }
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);

        $srcDir = $this->getSrcDir($this->getInstallPath($target), $target->getExtra());
        $openCartDir = $this->getOpenCartDir();

        $this->copyFiles($srcDir, $openCartDir, $target->getExtra());
        $this->runExtensionInstaller($this->getInstallPath($target), $target->getName(), $target->getExtra());
    }

    /**
     * { @inheritDoc }
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::uninstall($repo, $package);

        // TODO: remove files from opencart

    }

}