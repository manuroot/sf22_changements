<?php

namespace Application\ChangementsBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\QueryBuilder;

use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;

use Lexik\Bundle\FormFilterBundle\Filter\ORM\Expr;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\FilterTypeSharedableInterface;

/**
 * Embbed filter type.
 */
class EnvironnementFilterType extends AbstractType implements FilterTypeSharedableInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', 'filter_text');
     }

    public function getName()
    {
        return 'options_filter';
    }

    /**
     * This method aim to add all joins you need
     */
    public function addShared(FilterBuilderExecuterInterface $qbe)
    {
        $closure = function(QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
            // add the join clause to the doctrine query builder
            // the where clause for the label and color fields will be added automatically with the right alias later by the Lexik\Filter\QueryBuilderUpdater
            $filterBuilder->leftJoin($alias . '.options', 'opt');
        };

        // then use the query builder executor to define the join, the join's alias and things to do on the doctrine query builder.
        $qbe->addOnce($qbe->getAlias().'.options', 'opt', $closure);
    }
}