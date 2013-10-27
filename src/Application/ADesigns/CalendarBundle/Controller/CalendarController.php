<?php

namespace Application\ADesigns\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use ADesigns\CalendarBundle\Event\CalendarEvent;


use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
/*use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use Ob\HighchartsBundle\Highcharts\Highchart;
*/

class CalendarController extends Controller
{
    /**
     * Dispatch a CalendarEvent and return a JSON Response of any events returned.
     * 
     * @param Request $request
     * @return Response
     */
    public function loadCalendarAction(Request $request)
    {
        $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));
        
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('end'));
        
        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGURE, new CalendarEvent($startDatetime, $endDatetime))->getEvents();
        
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $return_events = array();
        
        foreach($events as $event) {
            $return_events[] = $event->toArray();    
        }
        
        $response->setContent(json_encode($return_events));
        
        return $response;
    }
}
