<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/10/09
 */

namespace Polidog\DependencyInjectionGeneratorBundle;


use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\GeneratorBundle\Model\Bundle;

class DependencyInjectionGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generateDirectory(Bundle $bundle)
    {
        $dir = $bundle->getTargetDirectory(). '/DependencyInjection';
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" exists but is a file.', realpath($dir)));
            }
            $files = scandir($dir);
            if ($files != array('.', '..')) {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not empty.', realpath($dir)));
            }
            if (!is_writable($dir)) {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not writable.', realpath($dir)));
            }
        }

        $parameters = array(
            'namespace' => $bundle->getNamespace(),
            'bundle' => $bundle->getName(),
            'format' => $bundle->getConfigurationFormat(),
            'bundle_basename' => $bundle->getBasename(),
            'extension_alias' => $bundle->getExtensionAlias(),
        );


        $this->renderFile('bundle/Extension.php.twig', $dir.'/'.$bundle->getBasename().'Extension.php', $parameters);
        $this->renderFile('bundle/Configuration.php.twig', $dir.'/Configuration.php', $parameters);
    }
}