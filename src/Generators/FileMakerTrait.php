<?php

namespace Tks\Larakits\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Container\Container;

trait FileMakerTrait
{
    protected function getAppNamespace()
    {
         return Container::getInstance()->getNamespace();
    }

    public function copyPreparedFiles($directory, $destination)
    {
        $fileSystem = new Filesystem;

        $files = $fileSystem->allFiles($directory);

        $fileDeployed = false;

        $fileSystem->copyDirectory($directory, $destination);

        foreach ($files as $file) {
            $fileContents = $fileSystem->get($file);
            $fileContentsPrepared = str_replace('{{App\}}', $this->getAppNamespace(), $fileContents);
            $fileDeployed = $fileSystem->put($destination.'/'.$file->getRelativePathname(), $fileContentsPrepared);
        }

        return $fileDeployed;
    }

}
