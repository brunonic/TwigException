<?php

namespace Kwrz\Bundle\TwigExceptionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kwrz_twig_exception');

        $rootNode
            ->children()
                ->scalarNode('with_debug')->defaultFalse()->end()
                ->arrayNode('handlers')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('regex')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('template')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('status_code')->prototype('scalar')->defaultValue(array())->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
    
}
