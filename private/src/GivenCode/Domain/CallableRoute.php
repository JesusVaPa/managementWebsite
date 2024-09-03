<?php
declare(strict_types=1);

namespace GivenCode\Domain;

use GivenCode\Exceptions\ValidationException;


class CallableRoute extends AbstractRoute
{

    private string $callable_string;

    public function __construct(string $uri, callable $closure)
    {
        $is_callable = is_callable($closure, false, $out_callable_name);
        if (!$is_callable) {
            throw new ValidationException("Callable route value for route [$uri] is not a callable value.");
        }
        $this->callable_string = $out_callable_name;
        parent::__construct($uri);
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function route(): void
    {
        call_user_func($this->callable_string);
    }
}