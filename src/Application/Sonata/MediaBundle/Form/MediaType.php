<?php

namespace Application\Sonata\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('enabled')
            ->add('providerName')
          /*  ->add('providerStatus')
            ->add('providerReference')
            ->add('providerMetadata')*/
            ->add('width')
            ->add('height')
            ->add('length')
            ->add('contentType')
            ->add('size')
         //   ->add('copyright')
            ->add('authorName')
            ->add('context')
          //  ->add('cdnIsFlushable')
            ->add('cdnFlushAt')
         //   ->add('cdnStatus')
            ->add('updatedAt')
            ->add('createdAt')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\MediaBundle\Entity\Media'
        ));
    }

    public function getName()
    {
        return 'application_sonata_mediabundle_mediatype';
    }
}
