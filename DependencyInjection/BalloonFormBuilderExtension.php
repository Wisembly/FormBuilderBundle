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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * BalloonFormBuilderExtension
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class BalloonFormBuilderExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('balloon_form_theme', $config['theme']);
        $container->setParameter('balloon_form_config', $config['fields']);
        $container->setParameter('balloon_form_entity', $config['form_entity']);
        $container->setParameter('balloon_field_entity', $config['field_entity']);
        $container->setParameter('balloon_answer_entity', $config['answer_entity']);
        $container->setParameter('balloon_field_answer_entity', $config['field_answer_entity']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('form.yml');
        $loader->load('twig.yml');
    }
}
