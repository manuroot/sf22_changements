<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DocumentsType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
                $builder
                        ->add('file')
                        ->add('name')
                        ;
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                    'data_class' => 'Application\RelationsBundle\Entity\Document',
            ));
        }

        public function getName()
        {
                return 'document_form';
        }
}