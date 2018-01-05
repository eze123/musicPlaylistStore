<?php
/*
 * Processes the web routes, deconstructing the core elements into their respective
 * controllers, models and classes.
 *     Eze Onukwube <ezeuchey2k@gmail.com>
 */
function processRoute($routes){
	$allRoutes = $routes->all();

    /** @var $params \Symfony\Component\Routing\Route */
    foreach ($allRoutes as $route => $params)
    {
	    $_SERVER["REQUEST_METHOD"] == "POST" ? $thisRoute = $_POST["route"] : $thisRoute = $_GET["route"];
		
        if ($route == $thisRoute)
	    {
		    $bolRouteFound = TRUE;
		
		    $routePath = $params->getPath();

		    $defaults = $params->getDefaults();
		
		    $routeController = $defaults['controller'];
		
		    $modelName = explode('Controller', $routeController);
		
		    $routeMethod = $defaults['method'];
		
		    $currentRoute = $params;
		
		    isset($defaults['genre']) ? $parameters = $defaults['genre'] : $parameters = null;
			isset($defaults['checkout']) ? $parameters = $defaults['checkout'] : $parameters;
			isset($defaults['purchase']) ? $parameters = $defaults['purchase'] : $parameters;
		    break;
	    }
    }

    if(isset($bolRouteFound) && $bolRouteFound)
    {
        $prefix = "MyApp\\Http\\Controllers\\".$routeController;
	    if(empty($parameters))
            $router = new $prefix;
	    else
		    $router = new $prefix($parameters);

        $router->{$routeMethod}();
	    $user   = new \MyApp\Http\Models\User;
    }

}



