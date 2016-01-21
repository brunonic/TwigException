<?php

/*
 * This file is part of the KwrzTwigExceptionBundle.
 *
 * Copyright 2014 Julien Demangeon <freelance@demangeon.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kwrz\Bundle\TwigExceptionBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Bundle Exception Controller
 *
 * Render Response based on request and exception
 * Inspired from the TwigExceptionController
 * Provide the same parameters to the view
 *
 * @author Julien Demangeon <freelance@demangeon.fr>
 */
class TwigExceptionController
{
    
    private $templating;
    private $logger;
    
    /**
     * Construct controller service with templating
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Symfony\Component\HttpKernel\Log\DebugLoggerInterface $logger
     */
    public function __construct(EngineInterface $templating, DebugLoggerInterface $logger = null)
    {

        $this->templating = $templating;
        $this->logger     = $logger;
    
    }
    
    /**
     * Process and render 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpKernel\Exception\FlattenException $exception
     * @param String $template
     * @return Response
     */
    public function showAction(Request $request, FlattenException $exception, $template)
    {
        
        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        
        $code = $exception->getStatusCode();
        
        return $this->templating->renderResponse($template, array(
            'status_code'    => $code,
            'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
            'exception'      => $exception,
            'logger'         => $this->logger,
            'currentContent' => $currentContent,
        ));
    
    }
    
    /**
     * Get And Clean Output Buffering
     * @param type $startObLevel
     * @return string
     */
    protected function getAndCleanOutputBuffering($startObLevel)
    {
        
        if (ob_get_level() <= $startObLevel) {
            return '';
        }
        
        Response::closeOutputBuffers($startObLevel + 1, true);
        
        return ob_get_clean();
        
    }
    
}
