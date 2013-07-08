<?php

namespace Application\RelationsBundle\Model;


class MyPaginator {

     /*==================================================================
      * 
     *  CREATION DU PAGINATOR
     * 
      =================================================================== */

    public function createpaginator($query, $num_perpage = 5) {

        $paginator = $this->get('knp_paginator');
        //$paginator->setUseOutputWalkers(true);
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable

        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        $pagination->setTemplate('ApplicationRelationsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $pagination->setSortableTemplate('ApplicationRelationsBundle:pagination:sortable_link.html.twig');
        return $pagination;
    }
}