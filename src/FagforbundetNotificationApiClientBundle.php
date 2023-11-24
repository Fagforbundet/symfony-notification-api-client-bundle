<?php

namespace Fagforbundet\NotificationApiClientBundle;

use Fagforbundet\NotificationApiClientBundle\Client\NotificationApiClient;
use Fagforbundet\NotificationApiClientBundle\Client\NotificationApiClientInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class FagforbundetNotificationApiClientBundle extends AbstractBundle {

  /**
   * @inheritDoc
   */
  public function configure(DefinitionConfigurator $definition): void {
    $definition->rootNode()
      ->addDefaultsIfNotSet()
      ->children()
        ->scalarNode('http_client')->defaultValue('http_client')->end()
        ->scalarNode('base_url')->defaultValue('https://api.meldinger.fagforbundet.dev')->end()
        ->scalarNode('token_provider')->defaultValue('hv.oidc.client_credentials_token_provider.default')->end()
      ->end()
    ;
  }

  /**
   * @inheritDoc
   */
  public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void {
    $alias = $this->getContainerExtension()->getAlias();

    $clientServiceId = $alias.'.client';
    $container->services()
      ->set($clientServiceId, NotificationApiClient::class)
        ->args([service($config['token_provider'])->ignoreOnInvalid(), service($config['http_client'])->nullOnInvalid(), $config['base_url']])
      ->alias(NotificationApiClientInterface::class, $clientServiceId)
    ;
  }

}
