<?php

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
                                    ->scalarNode('width')
                                    ->end()
                                    ->scalarNode('height')
                                    ->end()
                                ->end()
                            ->end()
                            ->enumNode('orientation')
                                ->info('The document orientation (portrait or landscape).')
                                ->defaultValue('P')
                                ->values(['P', 'L'])
                                ->beforeNormalization()
                                    ->always(function (string $v): string { return match ($v) { 'portrait' => 'P', 'landscape' => 'L', default => $v }; })
                                ->end()
                            ->end()
                            ->arrayNode('fonts')
                                ->children()
                                    ->scalarNode('default_font')
                                    ->end()
                                    ->scalarNode('default_size')
                                    ->end()
                                    ->arrayNode('custom_fonts')
                                        ->useAttributeAsKey('name')
                                        ->arrayPrototype()
                                            ->children()
                                                ->scalarNode('R')
                                                    ->info('Path to the regular version of the font.')
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
                                            ->always(function (array $fonts): array {
                                                $normalized = [];

                                                foreach ($fonts as $k => $v) {
                                                    $normalized[strtolower($k)] = $v;
                                                }

                                                return $normalized;
                                            })
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
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
