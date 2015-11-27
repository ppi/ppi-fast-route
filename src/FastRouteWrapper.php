<?php
namespace PPI\FastRoute\Wrapper;

use FastRoute\DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\Routing\RequestContext as SymfonyRequestContext;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class FastRouteWrapper implements UrlGeneratorInterface, RequestMatcherInterface
{

    /**
     * @var SymfonyRequestContext
     */
    protected $requestContext;

    /**
     * @var RouteCollector
     */
    protected $routeCollector;

    /**
     * @var DataGenerator
     */
    protected $dataGenerator;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @param RouteCollector $routeCollector
     * @param DataGenerator $dataGenerator
     * @param Dispatcher $dispatcher
     */
    public function __construct(RouteCollector $routeCollector, DataGenerator $dataGenerator, Dispatcher $dispatcher)
    {
        $this->routeCollector = $routeCollector;
        $this->dataGenerator = $dataGenerator;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param SymfonyRequestContext $context
     */
    public function setContext(SymfonyRequestContext $context)
    {
        $this->requestContext = $context;
    }

    /**
     * @return SymfonyRequestContext
     */
    public function getContext()
    {
        return $this->requestContext;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool|string $referenceType
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {

    }

    public function matchRequest(SymfonyRequest $request)
    {
        $result = $this->dispatcher->dispatch($method, $path);

        switch ($result[0]) {
            case Dispatcher::NOT_FOUND:
                // @todo - handle found not route
                // ... 404 Not Found
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                // @todo - handle method not allowed
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case Dispatcher::FOUND:
                // @todo - handle found route
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                break;
        }
    }

    /**
     * @param string $path
     * @return LaravelRouter
     * @throws \Exception
     */
    public function load($path)
    {

        if(!is_readable($path)) {
            throw new \InvalidArgumentException('Invalid fast route routes path found: ' . $path);
        }

        // localising the object so the $path file can reference $router;
        $r = $this->routeCollector;

        // The included file must return the laravel router
        include $path;

        if(!($r instanceof RouteCollector)) {
            throw new \Exception('Invalid return value from '
                . pathinfo($path, PATHINFO_FILENAME)
                . ' expected instance of RouteCollector'
            );
        }

        return $router;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

}