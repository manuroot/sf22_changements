<?php

namespace Application\ChangementsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
#use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ApplicationChangementsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        
        //$container = new ContainerBuilder();
        $container->setParameter('application_changements.session_timeout', $config['session_timeout']);
        $container->setParameter('application_changements.redirect_to', $config['redirect_to']);
        $container->setParameter('application_changements.expired_response', $config['expired_response']);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        //$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
