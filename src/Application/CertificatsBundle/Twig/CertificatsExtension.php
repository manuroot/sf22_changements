<?php

namespace Application\CertificatsBundle\Twig;

class CertificatsExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'price' => new \Twig_Filter_Method($this, 'priceFilter'),
            'var_dump'   => new \Twig_Filter_Function('var_dump'),
            'highlight'  => new \Twig_Filter_Method($this, 'highlight'),
   'highlightme'  => new \Twig_Filter_Method($this, 'highlightme'),
            
        );
    }
    
     public function highlight($sentence, $expr) {
        return preg_replace('/(' . $expr . ')/',
                            '<span style="color:red">\1</span>', $sentence);
    }
    
     public function highlightme($expr) {
        return '<h2><span style="color:red">' . $expr . '</span></h2>';
    }
    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$' . $price . 'monprice';

        return $price;
    }

    public function getName()
    {
        return 'certificats_twig_extension';
    }
}