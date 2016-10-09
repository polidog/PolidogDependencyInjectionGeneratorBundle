<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/10/09
 */

namespace Polidog\DependencyInjectionGeneratorBundle\Command;


use Polidog\DependencyInjectionGeneratorBundle\DependencyInjectionGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputInterface;
use Sensio\Bundle\GeneratorBundle\Model\Bundle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DependencyInjectionGeneratorCommand extends GeneratorCommand
{
    protected function configure()
    {
        $this
            ->setName('generate:bundle:dependency_inject_dir')
            ->setDescription('Generates a DependencyInject directory and files')
            ->setDefinition([
                new InputOption('namespace', '', InputOption::VALUE_REQUIRED, 'The namespace of the bundle to create'),
                new InputOption('format', '', InputOption::VALUE_REQUIRED, 'Use the format for configuration files (php, xml, yml, or annotation)'),
            ])
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $this->createBundleObject($input);
        /** @var DependencyInjectionGenerator $generator */
        $generator = $this->getGenerator();
        $generator->generateDirectory($bundle);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('namespace')) {
            $namespace = $this->getHelper('namespace')->askAndValidate(
                $output,
                'Please choose an namespace: ',
                function ($loginId) {
                    if (empty($loginId)) {
                        throw new \Exception('namespace can not be empty');
                    }

                    return $loginId;
                }
            );
            $input->setOption('namespace', $namespace);
        }

        if (!$input->getOption('format')) {
            $format = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose an format: ',
                function ($loginId) {
                    if (empty($loginId)) {
                        throw new \Exception('UserId can not be empty');
                    }

                    return $loginId;
                }
            );
            $input->setOption('format', $format);
        }
    }


    protected function createGenerator()
    {
        return new DependencyInjectionGenerator($this->getContainer()->get('filesystem'));
    }

    /**
     * @param InputInterface $input
     * @return Bundle
     */
    protected function createBundleObject(InputInterface $input)
    {
        $namespace = Validators::validateBundleNamespace($input->getOption('namespace'), true);
        $bundleName = strtr($namespace, array('\\Bundle\\' => '', '\\' => ''));

        $bundleName = Validators::validateBundleName($bundleName);
        $format = Validators::validateFormat($input->getOption('format'));
        $projectRootDirectory = $this->getContainer()->getParameter('kernel.root_dir').'/..';

        $dir = "src/";
        if (!$this->getContainer()->get('filesystem')->isAbsolutePath($dir)) {
            $dir = $projectRootDirectory.'/'.$dir;
        }
        // add trailing / if necessary
        $dir = '/' === substr($dir, -1, 1) ? $dir : $dir.'/';

        $bundle = new Bundle(
            $namespace,
            $bundleName,
            $dir,
            $format,
            true
        );

        return $bundle;
    }

}