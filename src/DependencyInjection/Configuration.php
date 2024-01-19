<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\DependencyInjection;

use Setono\SyliusCalloutPlugin\Form\Type\CalloutType;
use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutRule;
use Setono\SyliusCalloutPlugin\Model\CalloutTranslation;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_sylius_callout');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
                ->booleanNode('manual_triggering')
                    ->info('Set it to true if you have thousands of products in your store and/or want to trigger callout assign manually.')
                    ->defaultFalse()
                ->end()
                ->booleanNode('no_rules_eligible')
                    ->info('Set it to true if you want no rules to be treated as eligible (callout will be applied to all products).')
                    ->defaultFalse()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('callout')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Callout::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('repository')->defaultValue(CalloutRepository::class)->end()
                                        ->scalarNode('form')->defaultValue(CalloutType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('translation')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('options')->end()
                                        ->arrayNode('classes')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('model')->defaultValue(CalloutTranslation::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('callout_rule')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(CalloutRule::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
        ;
    }
}
