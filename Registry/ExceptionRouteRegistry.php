<?php

namespace Kwrz\Bundle\TwigExceptionBundle\Registry;

use Symfony\Component\HttpFoundation\Request;

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
     * @param type $request
     */
    public function matchRequest(Request $request, $expectedStatusCode = null)
    {
        
        $path_info = $request->getPathInfo();
        
        foreach($this->registry as $registry){
            
            if(count($registry['status_code']) > 0 && !in_array($expectedStatusCode, $registry['status_code'])){
                continue;
            }
            
            if (!preg_match('/' . $registry['regex'] . '/', $path_info)) {
                continue;
            }
            
            return $registry['template'];
            
        }
        
        return false;
        
    }
    
}
