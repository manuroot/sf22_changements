<?php

namespace Application\CentralBundle\Model;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class MesFiltresBundle extends Bundle
{
    
       /* ====================================================================
     * 
     *  CREATION DU PAGINATOR
     * 
      =================================================================== */

    /*public function createpaginator($query, $num_perpage = 5) {

        $paginator = $this->get('knp_paginator');
        //$paginator->setUseOutputWalkers(true);
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable

        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        $pagination->setTemplate('ApplicationChangementsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $pagination->setSortableTemplate('ApplicationChangementsBundle:pagination:sortable_link.html.twig');
        return $pagination;
    }*/

     protected function filter() {
        //  $message = "filter datas";
        $message = "";
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new CertificatsCenterFiltresType());
        $filterBuilder = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            //  $message = "reset filtres";
            $session->remove('certificatsControllerFilter');
            $query = $filterBuilder;
            return array($filterForm, $query, $message);
        }

        // datas filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["certificats_filter"];
            $filterForm->bind($datas);
            if ($filterForm->isValid()) {
                // $message .= " - filtre valide";
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
                $session->set('certificatsControllerFilter', $datas);
            } else {
                $query = $filterBuilder;
            }
            return array($filterForm, $query, $message);
        } else {
            if ($session->has('certificatsControllerFilter')) {

                $datas = $session->get('certificatsControllerFilter');
                $filterForm->bind($datas);
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
            }
            // ou pas
            else {
                // $message = "pas de session";
                $query = $filterBuilder;
            }
            return array($filterForm, $query, $message);
        }
    }

}
