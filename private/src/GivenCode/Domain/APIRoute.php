<?php
declare(strict_types=1);

namespace GivenCode\Domain;

use Exception;
use GivenCode\Abstracts\AbstractController;
use GivenCode\Enumerations\HTTPMethodsEnum;
use GivenCode\Exceptions\RequestException;
use GivenCode\Exceptions\ValidationException;


class APIRoute extends AbstractRoute
{
    private string $controllerClass;

    /**
     * TODO: documentation
     *
     * @param string $uri
     * @param string $controllerClass
     * @throws ValidationException
     */
    public function __construct(string $uri, string $controllerClass)
    {
        if (!class_exists($controllerClass)) {
            throw new ValidationException("APIRoute specified controller class [$controllerClass] does not exists.");
        }
        if (!is_a($controllerClass, AbstractController::class, true)) {
            throw new ValidationException("APIRoute specified controller class [$controllerClass] does not extend [" .
                AbstractController::class . "].");
        }
        parent::__construct($uri);
        $this->controllerClass = $controllerClass;
    }

    /**
     * TODO: documentation
     *
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     * @throws RequestException
     */
    public function route(): void
    {
        $method = HTTPMethodsEnum::getValue($_SERVER["REQUEST_METHOD"]);
        $controller_class = $this->getControllerClass();
        $controller = (new $controller_class());
        if (!($controller instanceof AbstractController)) {
            // this should not happen ever as it is validated inside the APIRoute constructor.
            throw new Exception("APIRoute specified controller class [$controller_class] does not extend [" .
                AbstractController::class . "].");
        }
        $controller->callHttpMethod($method);

    }
}