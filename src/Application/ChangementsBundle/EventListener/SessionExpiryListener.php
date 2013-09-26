<?php
namespace Application\ChangementsBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
//use Symfony\Component\Security\Core\SecurityContext;
//use Symfony\Component\Security\Core\SecurityContextInterface;
//use Doctrine\ORM\Event\LifecycleEventArgs;

/**
* Expires the session if idle too long
*/
class SessionExpiryListener implements EventSubscriberInterface
{
    /**
* @var ContainerInterface
*/
    private $container;
  //  protected $securityContext;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
     //   $this->securityContext = $securityContext;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        // Only operate on the master request
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
  //$user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
     //   if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
    
        $request = $event->getRequest();
        if (!$request->hasSession()){
            //|| ! $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return;
        }

        $session = $request->getSession();
        $session->start();
        $session_data = $session->getMetadataBag();

        // Expire sessions if unused for $idletimeout
        $idle_timeout = $this->container->getParameter('application_changements.session_timeout');
        if (time() - $session_data->getLastUsed() > $idle_timeout) {
            $session->invalidate();

            // Return custom response if provided
            $expiry_response = $this->container->getParameter('application_changements.expired_response');
            if ($expiry_response) {
                $event->setResponse($this->container->get($expiry_response));
                return;
            }

            // Redirect to route name if provided
            $path = $this->container->getParameter('application_changements.redirect_to');
            if ($path) {
                $url = $this->container->get('router')->generate($path);
                $response = new RedirectResponse($url);
                $event->setResponse($response);
                return;
            }

            throw new CredentialsExpiredException();
        }

    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('onKernelRequest', 127),
        );
    }
}