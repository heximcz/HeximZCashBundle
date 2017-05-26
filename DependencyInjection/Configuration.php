<?php

namespace Hexim\HeximZcashBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hexim_zcash');

        $rootNode
            ->children()
                ->scalarNode('rpc_url')->defaultValue("http://127.0.0.1")->end()
                ->scalarNode('rpc_password')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('rpc_user')->isRequired()->cannotBeEmpty()->end()
                ->integerNode('rpc_port')->defaultValue("8232")->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
