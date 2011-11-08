<?php

namespace Balloon\Bundle\FormBuilderBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('balloon_form_builder');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
            ->scalarNode('form_entity')
                ->defaultValue('Balloon\Bundle\FormBuilderBundle\Entity\Form')
            ->end()
            ->scalarNode('field_entity')
                ->defaultValue('Balloon\Bundle\FormBuilderBundle\Entity\FormField')
            ->end()
            ->arrayNode('fields')
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
