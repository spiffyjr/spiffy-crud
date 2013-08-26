<?php

namespace SpiffyCrud;

use ArrayObject;
use SpiffyCrud\Exception;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Part;
use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\Router\Http\Segment;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\PriorityList;
use Zend\Mvc\Router\RoutePluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\RequestInterface;
use Zend\Uri\Http as HttpUri;

class CrudRoute extends TreeRouteStack implements RouteInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * RouteInterface to match.
     *
     * @var RouteInterface
     */
    protected $route;

    /**
     * Whether the route may terminate.
     *
     * @var bool
     */
    protected $mayTerminate = true;

    /**
     * Child routes.
     *
     * @var mixed
     */
    public $childRoutes;

    /**
     * Create a new part route.
     *
     * @param  mixed              $route
     * @param  array|null         $childRoutes
     * @param  ArrayObject|null   $prototypes
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($route, array $childRoutes = null, ArrayObject $prototypes = null)
    {
        $this->routePluginManager = new RoutePluginManager();

        if (!$route instanceof RouteInterface) {
            $route = $this->routeFromArray($route);
        }

        if ($route instanceof self) {
            throw new Exception\InvalidArgumentException('Base route may not be a part route');
        }

        $this->route        = $route;
        $this->childRoutes  = $childRoutes;
        $this->prototypes   = $prototypes;
        $this->routes       = new PriorityList();
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

        $defaults['controller'] = $controller;
        $routes = array(
            'create' => new Literal(
                '/create',
                array_merge($defaults, array('action' => 'create'))
            ),
            'delete' => new Segment(
                sprintf('/:%s/delete', $identifier),
                array(),
                array_merge($defaults, array('action' => 'delete'))
            ),
            'update' => new Segment(
                sprintf('/:%s/update', $identifier),
                array(),
                array_merge($defaults, array('action' => 'update'))
            ),
        );

        return new CrudRoute(new Literal($route, array_merge($defaults, array('action' => 'read'))), $routes);
    }

    /**
     * {@inheritDoc}
     */
    public function match(RequestInterface $request, $pathOffset = null, array $options = array())
    {
        if ($pathOffset === null) {
            $pathOffset = 0;
        }

        $match = $this->route->match($request, $pathOffset, $options);

        if ($match !== null && method_exists($request, 'getUri')) {
            if ($this->childRoutes !== null) {
                $this->addRoutes($this->childRoutes);
                $this->childRoutes = null;
            }

            $nextOffset = $pathOffset + $match->getLength();

            $uri        = $request->getUri();
            $pathLength = strlen($uri->getPath());

            if ($this->mayTerminate && $nextOffset === $pathLength) {
                $query = $uri->getQuery();
                if ('' == trim($query) || !$this->hasQueryChild()) {
                    return $match;
                }
            }

            foreach ($this->routes as $name => $route) {
                if (($subMatch = $route->match($request, $nextOffset, $options)) instanceof RouteMatch) {
                    if ($match->getLength() + $subMatch->getLength() + $pathOffset === $pathLength) {
                        return $match->merge($subMatch)->setMatchedRouteName($name);
                    }
                }
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function assemble(array $params = array(), array $options = array())
    {
        if ($this->childRoutes !== null) {
            $this->addRoutes($this->childRoutes);
            $this->childRoutes = null;
        }

        $options['has_child'] = (isset($options['name']));

        $path   = $this->route->assemble($params, $options);
        $params = array_diff_key($params, array_flip($this->route->getAssembledParams()));

        if (!isset($options['name'])) {
            if (!$this->mayTerminate) {
                throw new Exception\RuntimeException('Part route may not terminate');
            } else {
                return $path;
            }
        }

        unset($options['has_child']);
        $options['only_return_path'] = true;
        $path .= parent::assemble($params, $options);

        return $path;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssembledParams()
    {
        return array();
    }
}
