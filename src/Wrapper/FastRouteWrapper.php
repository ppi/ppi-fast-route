<?php
namespace PPI\FastRoute\Wrapper;

use FastRoute\DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\Routing\RequestContext as SymfonyRequestContext;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FastRouteWrapper implements UrlGeneratorInterface, RequestMatcherInterface
{

    /**
     * @var SymfonyRequestContext
     */
    protected $requestContext;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
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
        $method = $request->getMethod();
        $path = $request->getPathInfo();
        $routeInfo = $this->dispatcher->dispatch($method, $path);

        switch ($routeInfo[0]) {
            case Dispatcher::METHOD_NOT_ALLOWED:
//                $allowedMethods = $routeInfo[1];
                throw new MethodNotAllowedException;

            case Dispatcher::FOUND:
                $vars = $routeInfo[2];
                $vars['_route'] = $method . ' ' . $path;
                $vars['_controller'] = $routeInfo[1];
                $vars['_module'] = $this->getModuleName();
                return $vars;

            case Dispatcher::NOT_FOUND:
                throw new ResourceNotFoundException;

            default:
                throw new ResourceNotFoundException;
        }
    }

    /**
     * @param string $path
     * @return LaravelRouter
     * @throws \Exception
     */


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
