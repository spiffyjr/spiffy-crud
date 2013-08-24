<?php

namespace SpiffyCrud;

use SpiffyCrud\Exception;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Stdlib\RequestInterface;

class CrudRoute extends TreeRouteStack implements RouteInterface
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @param string $route
     * @param string $controller
     * @param array $defaults
     * @param string $identifier
     */
    public function __construct($route, $controller, $defaults = array(), $identifier = 'id')
    {
        $this->route      = $route;
        $this->controller = $controller;
        $this->defaults   = $defaults;
        $this->identifier = $identifier;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public static function factory($options = array())
    {
        $identifier = isset($options['identifier']) ? $options['identifier'] : 'id';
        $route      = isset($options['route']) ? $options['route'] : null;
        $defaults   = isset($options['defaults']) ? $options['defaults'] : array();
        $controller = isset($options['controller']) ? $options['controller'] : array();
        $controller = !$controller && isset($defaults['controller']) ? $defaults['controller'] : array();

        if (!$route) {
            throw new Exception\MissingRouteException('Missing route.');
        }

        if (!$controller) {
            throw new Exception\MissingControllerException(sprintf(
                'No controller given for route "%s".',
                $route
            ));
        }

        $instance = new CrudRoute(
            $route,
            $controller,
            $defaults,
            $identifier
        );

        $config = array(
            'create' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => sprintf('%s/create', $route),
                    'defaults' => array_merge($defaults, array(
                        'controller' => $controller,
                        'action' => 'create'
                    ))
                ),
            ),

            'delete' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => sprintf('%s/:%s/delete', $route, $identifier),
                    'defaults' => array_merge($defaults, array(
                        'controller' => $controller,
                        'action' => 'delete'
                    ))
                )
            ),

            'update' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => sprintf('%s/:%s/update', $route, $identifier),
                    'defaults' => array_merge($defaults, array(
                        'controller' => $controller,
                        'action' => 'update'
                    ))
                )
            )
        );
        $instance->addRoutes($config);

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function assemble(array $params = array(), array $options = array())
    {
        if (!isset($options['name'])) {
            return $this->route;
        }
        return parent::assemble($params, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function match(RequestInterface $request, $pathOffset = null)
    {
        if (!$request instanceof HttpRequest) {
            return null;
        }

        $uri  = $request->getUri();
        $path = $uri->getPath();

        $defaults = array_merge($this->defaults, array(
            'controller' => $this->controller,
            'action'     => 'read',
        ));

        if ($pathOffset !== null) {
            if ($pathOffset >= 0 && strlen($path) >= $pathOffset && !empty($this->route)) {
                if (strpos($path, $this->route, $pathOffset) === $pathOffset) {
                    return new RouteMatch($defaults, strlen($this->route));
                }
            }

            return null;
        }

        if ($path === $this->route) {
            return new RouteMatch($defaults, strlen($this->route));
        }

        return parent::match($request, $pathOffset);
    }

    /**
     * {@inheritDoc}
     */
    public function getAssembledParams()
    {
        return array();
    }
}
