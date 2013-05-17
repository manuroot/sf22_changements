<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel {

    public function registerBundles() {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            //----------------------------------------------------
            //MOPA
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            // MENUS
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new APY\DataGridBundle\APYDataGridBundle(),
            // FOSUSER   
            new FOS\UserBundle\FOSUserBundle(),
            // SONATA
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\MarkItUpBundle\SonataMarkItUpBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            new Sonata\NewsBundle\SonataNewsBundle(),
            new FrequenceWeb\Bundle\CalendRBundle\FrequenceWebCalendRBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            // new Bc\Bundle\BootstrapBundle\BcBootstrapBundle(),
            //   new \Rizza\CalendarBundle\RizzaCalendarBundle(),
            // MES APPLICATIONS
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle(),
            //new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle(),
            new Application\CertificatsBundle\ApplicationCertificatsBundle(),
            //new Application\MyNotesBundle\ApplicationMyNotesBundle(),
           // new Application\EservicesBundle\ApplicationEservicesBundle(),
            new Application\ChangementsBundle\ApplicationChangementsBundle(),
            new Application\RelationsBundle\ApplicationRelationsBundle(),
            //new Ivory\GoogleMapBundle\IvoryGoogleMapBundle(),
            //new Application\EpostBundle\ApplicationEpostBundle(),
            //====================================================
            // FORM FILTER
            //====================================================
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new Savvy\FilterNatorBundle\SavvyFilterNatorBundle(),
             //====================================================
            // FOSCOMMENT
            //====================================================
            
               new FOS\RestBundle\FOSRestBundle(),
        //new FOS\CommentBundle\FOSCommentBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle($this),

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
          //  $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader) {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

}
