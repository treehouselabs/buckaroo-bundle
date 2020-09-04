<?php

namespace TreeHouse\BuckarooBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('tree_house_buckaroo');

        $config = $treeBuilder->getRootNode()->children();
        $config
            ->booleanNode('test_mode')
            ->info('Whether to enable test mode')
            ->defaultFalse()
        ;
        $config
            ->scalarNode('secret_key')
            ->info('The secret key used to generate signatures with')
            ->isRequired()
            ->cannotBeEmpty()
        ;
        $config
            ->scalarNode('website_key')
            ->info('Buckaroo identifier for your website')
            ->isRequired()
            ->cannotBeEmpty()
        ;

        return $treeBuilder;
    }
}
