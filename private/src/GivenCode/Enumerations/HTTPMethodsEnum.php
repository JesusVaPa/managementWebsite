<?php
declare(strict_types=1);

namespace GivenCode\Enumerations;

use JetBrains\PhpStorm\Pure;


enum HTTPMethodsEnum: string
{
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case DELETE = "DELETE";

    /**
     * TODO: Function documentation
     *
     * @static
     * @param string $methodString
     * @return HTTPMethodsEnum
     */
    #[Pure] public static function getValue(string $methodString): HTTPMethodsEnum
    {
        return self::from(strtoupper(trim($methodString)));
    }
}
