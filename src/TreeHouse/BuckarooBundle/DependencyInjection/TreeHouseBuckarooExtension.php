<?php

namespace TreeHouse\BuckarooBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TreeHouseBuckarooExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('tree_house.buckaroo.test_mode', $config['test_mode']);
        $container->setParameter('tree_house.buckaroo.secret_key', $config['secret_key']);
        $container->setParameter('tree_house.buckaroo.website_key', $config['website_key']);
    }
}
