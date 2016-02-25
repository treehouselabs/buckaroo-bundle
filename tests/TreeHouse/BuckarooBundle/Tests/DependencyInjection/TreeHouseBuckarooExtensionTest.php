<?php

namespace TreeHouse\BuckarooBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use TreeHouse\BuckarooBundle\Client\NvpClient;
use TreeHouse\BuckarooBundle\DependencyInjection\TreeHouseBuckarooExtension;
use TreeHouse\BuckarooBundle\SignatureGenerator;

class TreeHouseBuckarooExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_provides_a_client()
    {
        $this->load([
            'test_mode' => true,
            'secret_key' => 's3cr4t',
            'website_key' => 'mywebsite',
        ]);

        // assert that the right parameters have been set
        $this->assertContainerBuilderHasParameter('tree_house.buckaroo.test_mode', true);
        $this->assertContainerBuilderHasParameter('tree_house.buckaroo.secret_key', 's3cr4t');
        $this->assertContainerBuilderHasParameter('tree_house.buckaroo.website_key', 'mywebsite');

        // assert a client definition is available, with the right parameters injected
        $clientId = 'tree_house.buckaroo.nvp_client';
        $this->assertContainerBuilderHasService($clientId, NvpClient::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument($clientId, 2, '%tree_house.buckaroo.website_key%');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument($clientId, 3, '%tree_house.buckaroo.test_mode%');

        // assert a signature generator is available, with the right parameters injected
        $generatorId = 'tree_house.buckaroo.signature_generator';
        $this->assertContainerBuilderHasService($generatorId, SignatureGenerator::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument($generatorId, 0, '%tree_house.buckaroo.secret_key%');
    }

    /**
     * @test
     */
    public function it_is_not_in_test_mode_by_default()
    {
        $this->load([
            'secret_key' => 's3cr4t',
            'website_key' => 'mywebsite',
        ]);

        $this->assertContainerBuilderHasParameter('tree_house.buckaroo.test_mode', false);
    }

    /**
     * @test
     * @dataProvider getIncompleteConfigurations
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     *
     * @param array $config
     */
    public function it_fails_without_certain_elements(array $config)
    {
        $this->load($config);
    }

    /**
     * @return array
     */
    public function getIncompleteConfigurations()
    {
        return [
            [[
                'test_mode' => true,
                'website_key' => 'mywebsite',
            ]],
            [[
                'test_mode' => true,
                'secret_key' => '',
                'website_key' => 'mywebsite',
            ]],
            [[
                'test_mode' => true,
                'secret_key' => 'secret',
            ]],
            [[
                'test_mode' => true,
                'secret_key' => 'secret',
                'website_key' => '',
            ]],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getContainerExtensions()
    {
        return [new TreeHouseBuckarooExtension()];
    }
}
