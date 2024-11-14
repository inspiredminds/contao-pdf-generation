<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoPdfGeneration\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContaoPdfGenerationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        (new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config')))
            ->load('services.yaml')
        ;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('contao_pdf_generation.configurations', $config['configurations'] ?? []);
    }
}
