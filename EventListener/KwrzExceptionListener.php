<?php

/*
 * This file is part of the KwrzTwigExceptionBundle.
 *
 * Copyright 2014 Julien Demangeon <freelance@demangeon.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kwrz\Bundle\TwigExceptionBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Kwrz\Bundle\TwigExceptionBundle\Controller\TwigExceptionController as Controller;
use Kwrz\Bundle\TwigExceptionBundle\Registry\ExceptionRouteRegistry as Registry;

/**
 * Kernel Exception Listener Class
 *
 * Listen to the kernel.exception event and provide new response from config.
 *
 * @author Julien Demangeon <freelance@demangeon.fr>
 */
class KwrzExceptionListener
{

    private $controller;
    private $registry;
    private $activated;

    /**
     * Construct the entry point to the exception controller
     * @param \Kwrz\Bundle\TwigExceptionBundle\Controller\TwigExceptionController $controller
     * @param \Kwrz\Bundle\TwigExceptionBundle\Registry\ExceptionRouteRegistry $registry
     * @param boolean $debug Kernel debug status
     * @param boolean $with_debug Handle event in debug mode ?
     */
    public function __construct(Controller $controller, Registry $registry, $debug, $with_debug)
    {
    
        $this->controller   = $controller;
        $this->registry     = $registry;
        $this->activated    = (boolean) (!$debug || $with_debug);
        
    }
    
    /**
     * Capture response from exception event and change it from configuration
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     * @return
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        
        if($event->isPropagationStopped() || !$this->activated){
            return;
        }
        
        $request   = $event->getRequest();
        $exception = $event->getException();
        
        $response = $this->getAdaptedExceptionResponse($exception);
        
        if(($template = $this->isRegisteredEventRequest($request, $response->getStatusCode()))){
            
            $decoratedResponse = $this->createDecoratedExceptionResponse($request, $exception, $template);
            $response->setContent($decoratedResponse->getContent());

            $event->setResponse($response);

        }
        
        return;

    }
    
    /**
     * Check if request match any of registered routes in configuration
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return mixed Expected Response Status Code
     */
    private function isRegisteredEventRequest(Request $request, $expectedStatusCode = null)
    {
    
        return $this->registry->matchRequest($request, $expectedStatusCode);
        
    }
    
    /**
     * Create a new HttpResponse from the following exception
     * If exception is a HttpExceptionInterface, hold status and headers
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Exception $exception
     * @param String $template
     * @return Response
     */
    private function createDecoratedExceptionResponse(Request $request, \Exception $exception, $template)
    {
        
        $flattenException = FlattenException::create($exception);
        
        return $this->controller->showAction($request, $flattenException, $template);
        
    }
    
    /**
     * Return adapted (status code) Exception response from exception
     * @param \Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getAdaptedExceptionResponse(\Exception $exception)
    {
        
        $response = new Response();
        
        if ($exception instanceof HttpExceptionInterface){
            
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        
        }
        else {
            
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            
        }
        
        return $response;
        
    }
    
}
