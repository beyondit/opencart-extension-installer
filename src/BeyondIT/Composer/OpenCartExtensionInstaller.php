<?php

namespace BeyondIT\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;

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
     * { @inheritDoc }
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        $srcDir = $this->getSrcDir($this->getInstallPath($package), $package->getExtra());
        $openCartDir = $this->getOpenCartDir();

        $this->copyFiles($srcDir, $openCartDir, $package->getExtra());
    }

    /**
     * { @inheritDoc }
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);

        // TODO: update files from opencart
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