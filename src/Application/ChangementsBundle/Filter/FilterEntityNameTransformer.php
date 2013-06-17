<?php


namespace Application\ChangementsBundle\Filter;

use Lexik\Bundle\FormFilterBundle\Filter\Transformer\FilterTransformerInterface;
 
use Symfony\Component\Form\FormInterface;
 
class FilterEntityNameTransformer implements FilterTransformerInterface
{
/**
* {@inheritDoc}
* @see Lexik\Bundle\FormFilterBundle\Filter\Transformer.FilterTransformerInterface::transform()
*/
public function transform(FormInterface $form)
{
$values = array('value' => $form->getData());
$values['field'] = $form->getConfig()->getOptions()['association_field'];
 
return $values;
}
}