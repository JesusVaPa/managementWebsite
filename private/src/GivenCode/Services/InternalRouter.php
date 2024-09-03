<?php
declare(strict_types=1);

namespace GivenCode\Services;

use Controllers\PermissionController;
use Controllers\UserController;
use Controllers\GroupController;
use Controllers\LoginController;
use Controllers\PageNavigator;
use GivenCode\Abstracts\IService;
use GivenCode\Domain\AbstractRoute;
use GivenCode\Domain\APIRoute;
use GivenCode\Domain\CallableRoute;
use GivenCode\Domain\RouteCollection;
use GivenCode\Domain\WebpageRoute;
use GivenCode\Exceptions\RequestException;
use GivenCode\Exceptions\ValidationException;


class InternalRouter implements IService
{

    private string $uriBaseDirectory;
    private RouteCollection $routes;

    /**
     * @param string $uri_base_directory
     * @throws ValidationException
     */
    public function __construct(string $uri_base_directory = "")
    {
        $this->uriBaseDirectory = $uri_base_directory;
        $this->routes = new RouteCollection();
        $this->routes->addRoute(new APIRoute("/api/userDTO", UserController::class));
        $this->routes->addRoute(new APIRoute("/api/login", LoginController::class));
        $this->routes->addRoute(new APIRoute("/api/groups", GroupController::class));
        $this->routes->addRoute(new APIRoute("/api/users", UserController::class));
        $this->routes->addRoute(new APIRoute("/api/permissions", PermissionController::class));
        $this->routes->addRoute(new WebpageRoute("/index", "users.php"));
        $this->routes->addRoute(new WebpageRoute("/", "users.php"));
        $this->routes->addRoute(new CallableRoute("/pages/login", [PageNavigator::class, "loginPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/groups", [PageNavigator::class, "groupsManagementPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/users", [PageNavigator::class, "usersManagementPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/permissions", [PageNavigator::class, "permissionsManagementPage"]));
    }

    public function route(): void
    {
        $path = REQUEST_PATH;
        $route = $this->routes->match($path);

        if (is_null($route)) {
            // route not found
            throw new RequestException("Route [$path] not found.", 404);
        }

        $route->route();

    }

    /**
     * Adds an {@see AbstractRoute internal route definition} to the {@see InternalRouter}'s {@see RouteCollection}.
     *
     * @param AbstractRoute $route The route definition to add to the route collection.
     * @return void
     * @throws ValidationException
     *
     * @user Marc-Eric Boury
     * @since  2024-04-12
     */
    public function addRoute(AbstractRoute $route): void
    {
        $this->routes->addRoute($route);
    }
}