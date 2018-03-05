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
use Symfony\Component\HttpKernel\Log\Logger;

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
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param \Twig_Environment $twig
     * @param Logger $logger
     */
    public function __construct(\Twig_Environment $twig, Logger $logger = null)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * Process and render
     *
     * @param Request $request
     * @param FlattenException $exception
     * @param string $template
     *
     * @return Response
     */
    public function showAction(Request $request, FlattenException $exception, $template)
    {
        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        $code = $exception->getStatusCode();

        return $this->twig->render(
            $template,
            [
                'status_code' => $code,
                'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception' => $exception,
                'logger' => $this->logger,
                'currentContent' => $currentContent,
            ]
        );
    }

    /**
     * Get And Clean Output Buffering
     *
     * @param type $startObLevel
     *
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
