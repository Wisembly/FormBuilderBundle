<?php

/*
 * This file is part of the BalloonFormBuilderBundle
 *
 * (c) Balloon <contact@balloonup.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Balloon\Bundle\FormBuilderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('balloon_form_builder');

        $rootNode
            ->children()
            ->arrayNode('resources')
                ->defaultValue(array())
                ->validate()
                    ->ifTrue(function($v) { return empty($v); })
                    ->then(function($v){
                        return array();
                    })
                ->end()
                ->prototype('scalar')->end()
            ->end()
            ->scalarNode('form_entity')
                ->defaultValue('Balloon\Bundle\FormBuilderBundle\Entity\Form')
            ->end()
            ->scalarNode('field_entity')
                ->defaultValue('Balloon\Bundle\FormBuilderBundle\Entity\FormField')
            ->end()
            ->scalarNode('answer_entity')
                ->defaultValue('Balloon\Bundle\FormBuilderBundle\Entity\FormAnswer')
            ->end()
            ->scalarNode('field_answer_entity')
                ->defaultValue('Balloon\Bundle\FormBuilderBundle\Entity\FormFieldAnswer')
            ->end()
            ->arrayNode('fields')
                ->isRequired()
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->useAttributeAsKey('name')
                        ->prototype('variable')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
