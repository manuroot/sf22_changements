<?php


namespace Application\ChangementsBundle\Filter;


use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\FilterTypeInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\AbstractFilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
 
use Lexik\Bundle\FormFilterBundle\Filter\Expr;
 
class EntityNameFilterType extends AbstractFilterType implements FilterTypeInterface {
/**
* {@inheritdoc}
*/
public function getName()
{
return 'filter_entity_name';
}
/**
* {@inheritdoc}
*/
public function getParent()
{
return 'text';
}
/**
* {@inheritdoc}
*/
public function getTransformerId()
{
return 'lexik_form_filter.transformer.entity_name';
}
/**
* {@inheritdoc}
*/
public function setDefaultOptions(OptionsResolverInterface $resolver)
{
parent::setDefaultOptions($resolver);
$resolver->setDefaults(array(
'association_field' => "",
));
}
/**
* {@inheritdoc}
*/
public function applyFilter(QueryBuilder $queryBuilder, Expr $expr, $field, array $values)
{
if (!empty($values['value']) && !empty($values['field'])) {
$queryBuilder->andWhere($expr->stringLike($values['field'], $values['value'], TextFilterType::PATTERN_CONTAINS));
}
}
}