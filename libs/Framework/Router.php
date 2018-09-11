<?php 

namespace Framework;

class Router {

    protected $_xml_doc = null;
    protected $_routes = [];

    public function __construct() {
        
        $file = new \DOMDocument('1.0', 'utf-8');
        if ($file->load($_SERVER['DOCUMENT_ROOT'] . '/config/routes.xml') !== FALSE) {

            $routes = $file->getElementsByTagName('route');

            foreach ($routes as $key => $route) {

                if ($route->hasAttribute('url') && $route->hasAttribute('controller') && $route->hasAttribute('action')) {

                    $new_route = new Route(
                        $route->getAttribute('url'),
                        $route->getAttribute('controller'),
                        $route->getAttribute('action')
                    );

                    array_push($this->_routes, $new_route);

                } else {
                    die('One of the routes is not configured correctly.');
                }
            }

        } else {
            die('The config file could not be loaded.');
        }
    }

    public function isRouteMatching($url, Route $route) {

        if ($route->get_url() === $url) return true;

        return false;
    }

    /**
     * Check if the actual url matches a registered route.
     * Returns False if no matching route is found.
     * If found, returns the matched route.
     * 
     * @param string $url
     * @return Mixed
     */
    public function getMatchedRoute($url) {

        foreach ($this->_routes as $key => $route) {
            if ($url === $route->get_url()) return $route;
        }

        return false;
    }

}