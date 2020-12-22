<?php

namespace BeyondIT\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class OpenCartExtensionInstallerPlugin implements PluginInterface
{
    protected $packageType = 'opencart-extension';

    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new OpenCartExtensionInstaller($io, $composer, $this->packageType);
        $composer->getInstallationManager()->addInstaller($installer);
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // do nothing
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // do nothing
    }
}