<?php

/*
 * This file is part of the KwrzTwigExceptionBundle.
 *
 * Copyright 2014 Julien Demangeon <freelance@demangeon.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kwrz\Bundle\TwigExceptionBundle\Registry;

use Symfony\Component\HttpFoundation\Request;

/**
 * Exception route registry
 *
 * List all regex routes based on configuration and provides matching method on them.
 *
 * @author Julien Demangeon <freelance@demangeon.fr>
 */
class ExceptionRouteRegistry
{

    private $registry;
    
    /**
     * Construct registry from configuration
     * @param array $paths
     */
    public function __construct(array $paths)
    {
        
        $this->registry = $paths;
        
    }
    
    /**
     * Test if request is in routeCollection and return template
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed $expectedStatusCode
     * @return mixed
     */
    public function matchRequest(Request $request, $expectedStatusCode = null)
    {
        
        $path_info = $request->getPathInfo();
        
        foreach($this->registry as $registry){
            
            // Check if the expectedStatusCode is part of the authorized status
            
            if(count($registry['status_code']) > 0 && !in_array($expectedStatusCode, $registry['status_code'])){
                continue;
            }
            
            // Check if the regex match the current path info
            
            if (!preg_match('/' . $registry['regex'] . '/', $path_info)) {
                continue;
            }
            
            return $registry['template'];
            
        }
        
        return false;
        
    }
    
}
