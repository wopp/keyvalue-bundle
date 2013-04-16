<?php

namespace Wopp\KeyValueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    const DOCUMENT_MANAGER = 'document_manager';
    const REDIS_CONNECTION = 'redis_connection';

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wopp_key_value');
        $rootNode
            ->children()
            ->scalarNode(self::DOCUMENT_MANAGER)->defaultValue('default')->end()
            ->scalarNode(self::REDIS_CONNECTION)->defaultValue('tcp://localhost:6379')->end()
            ->end();

        return $treeBuilder;
    }
}
