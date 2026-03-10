<?php

declare(strict_types=1);

/*
 * This file is part of the Contao PDF Generation extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPdfGeneration\DependencyInjection;

use InspiredMinds\ContaoPdfGeneration\ContaoPdfGeneration;
use InspiredMinds\ContaoPdfGeneration\EventListener\DataContainer\LayoutPdfGenerationConfigOptionsListener;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContaoPdfGenerationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $pdfGenerationConfigs = $config['configurations'] ?? [];

        if (!$pdfGenerationConfigs) {
            return;
        }

        (new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config')))
            ->load('services.yaml')
        ;

        $container
            ->getDefinition(LayoutPdfGenerationConfigOptionsListener::class)
            ->setArgument('$pdfGenerationConfigs', $pdfGenerationConfigs)
        ;

        $container
            ->getDefinition(ContaoPdfGeneration::class)
            ->setArgument('$pdfGenerationConfigs', $pdfGenerationConfigs)
        ;
    }
}
