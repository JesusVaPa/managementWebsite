<?php
declare(strict_types=1);

namespace GivenCode\Domain;

abstract class AbstractRoute
{

    private string $routePath;

    public function __construct(string $uri)
    {
        $this->setRoutePath($uri);
    }


    /**
     * TODO: documentation
     *
     * @return string
     */
    public function getRoutePath(): string
    {
        return $this->routePath;
    }

    /**
     * TODO: Function documentation
     *
     * @param string $route_path
     * @return void
     */
    protected function setRoutePath(string $route_path): void
    {
        if (!str_contains($route_path, PRJ_ROOT_DIRNAME)) {
            $route_path = WEB_DIRECTORY_SEPARATOR . PRJ_ROOT_DIRNAME . $route_path;
        }
        $this->routePath = $route_path;
    }

    /**
     * Executes the code associated with the current route.
     *
     * @return void
     */
    abstract public function route(): void;

}