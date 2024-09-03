<?php
declare(strict_types=1);

namespace Services;

use Debug;
use Exception;
use DTOs\UserDTO;
use GivenCode\Abstracts\IService;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;

class LoginService implements IService
{

    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public static function requireAdmin(): bool
    {
        $return_value = false;
        if (!empty($_SESSION["LOGGED_IN_USER"]) && ($_SESSION["LOGGED_IN_USER"] instanceof UserDTO)) {
            $requiredUser = (new UserService())->getUserById(1);
            $user_object = $_SESSION["LOGGED_IN_USER"];
            if ($user_object->getId() === $requiredUser->getId()) {
                $return_value = true;
            }
        }
        return $return_value;
    }

    public static function isUserLoggedIn(): bool
    {
        $return_val = false;
        if (!empty($_SESSION["LOGGED_IN_USER"]) && ($_SESSION["LOGGED_IN_USER"] instanceof UserDTO)) {
            $return_val = true;
        }
        Debug::log(("Is logged in user check result: [" . $return_val)
            ? "true"
            : ("false" . "]" .
                ($return_val ? (" id# [" . $_SESSION["LOGGED_IN_USER"]->getId() . "].") : ".")));
        return $return_val;
    }

    public static function redirectToLogin(): void
    {
        header("Location: " . WEB_ROOT_DIR . "pages/login?from=" . $_SERVER["REQUEST_URI"]);
        http_response_code(303);
        exit();
    }

    public static function requireLoggedInUser(): void
    {
        if (!self::isUserLoggedIn()) {
            // not logged in, do a redirection to the login page.
            // Note that I am adding a 'from' URL parameter that will be used to send the user to the right page after login
            self::redirectToLogin();
        }
    }

    public function doLogout(): void
    {
        $_SESSION["LOGGED_IN_USER"] = null;
        Debug::debugToHtmlTable($_SESSION);
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException|Exception
     */
    public function doLogin(int $userId): void
    {
        $user = $this->userService->getUserById($userId);
        if (is_null($user)) {
            throw new Exception("User id# [$userId] not found.", 404);
        }
        $_SESSION["LOGGED_IN_USER"] = $user;
    }

}