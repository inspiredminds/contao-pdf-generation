<?php

declare(strict_types=1);

/*
 * This file is part of the Contao PDF Generation extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPdfGeneration\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_pdf_generation');
        $treeBuilder
            ->getRootNode()
            ->children()
                ->arrayNode('configurations')
                    ->info('Allows you to define different PDF generation configurations which can then be referenced during the generation.')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('format')
                                ->info('The document format.')
                                ->defaultValue('A4')
                            ->end()
                            ->arrayNode('custom_format')
                                ->info('Custom format in millimeters. Overrides the regular format.')
                                ->children()
                                    ->integerNode('width')
                                    ->end()
                                    ->integerNode('height')
                                    ->end()
                                ->end()
                            ->end()
                            ->enumNode('orientation')
                                ->info('The document orientation (portrait or landscape).')
                                ->defaultValue('P')
                                ->values(['P', 'L'])
                                ->beforeNormalization()
                                    ->always(static fn (string $v): string => match ($v) {
                                        'portrait' => 'P', 'landscape' => 'L', default => $v,
                                    })
                                ->end()
                            ->end()
                            ->arrayNode('fonts')
                                ->children()
                                    ->scalarNode('default_font')
                                    ->end()
                                    ->integerNode('default_size')
                                    ->end()
                                    ->arrayNode('custom_fonts')
                                        ->info('Here you can define custom fonts and the path to their TTF files (https://mpdf.github.io/fonts-languages/fonts-in-mpdf-7-x.html).')
                                        ->useAttributeAsKey('name')
                                        ->arrayPrototype()
                                            ->children()
                                                ->scalarNode('R')
                                                    ->info('Path to the regular version of the font.')
                                                    ->example('%kernel.project_dir%/pdf/foo.ttf')
                                                ->end()
                                                ->scalarNode('B')
                                                    ->info('Path to the bold version of the font.')
                                                ->end()
                                                ->scalarNode('I')
                                                    ->info('Path to the italic version of the font.')
                                                ->end()
                                                ->scalarNode('BI')
                                                    ->info('Path to the bold italic version of the font.')
                                                ->end()
                                                ->scalarNode('useOTL')
                                                    ->info('https://mpdf.github.io/fonts-languages/opentype-layout-otl.html#useotl')
                                                ->end()
                                                ->integerNode('useKashida')
                                                    ->info('https://mpdf.github.io/fonts-languages/opentype-layout-otl.html#usekashida')
                                                    ->min(0)
                                                    ->max(100)
                                                ->end()
                                            ->end()
                                        ->end()
                                        ->beforeNormalization()
                                            ->always(
                                                static function (array $fonts): array {
                                                    $normalized = [];

                                                    foreach ($fonts as $k => $v) {
                                                        $normalized[strtolower($k)] = $v;
                                                    }

                                                    return $normalized;
                                                },
                                            )
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('margins')
                                ->info('Document margins in millimeters.')
                                ->children()
                                    ->scalarNode('left')
                                    ->end()
                                    ->scalarNode('right')
                                    ->end()
                                    ->scalarNode('top')
                                    ->end()
                                    ->scalarNode('bottom')
                                    ->end()
                                    ->scalarNode('header')
                                    ->end()
                                    ->scalarNode('footer')
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('page_template')
                                ->info('Replaces the template of the page layout.')
                                ->defaultValue('pdf_default')
                            ->end()
                            ->scalarNode('pdf_template')
                                ->info('Specifies an external PDF file to use as a template (https://mpdf.github.io/reference/mpdf-functions/setdoctemplate.html).')
                                ->example('%kernel.project_dir%/pdf/template.pdf')
                            ->end()
                            ->scalarNode('pdf_appendix')
                                ->info('Specifies an external PDF file to use as an appendix.')
                                ->example('%kernel.project_dir%/pdf/appendix.pdf')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
