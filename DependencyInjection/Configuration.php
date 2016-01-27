<?php

/*
 * This file is part of the KwrzTwigExceptionBundle.
 *
 * Copyright 2014 Julien Demangeon <freelance@demangeon.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kwrz\Bundle\TwigExceptionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration
 * 
 * @author Julien Demangeon <freelance@demangeon.fr>
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
                ->scalarNode('enabled')->defaultTrue()->end()
                ->scalarNode('with_debug')->defaultFalse()->end()
                ->arrayNode('handlers')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')->end()
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
