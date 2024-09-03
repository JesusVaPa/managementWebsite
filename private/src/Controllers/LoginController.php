<?php
declare(strict_types=1);


namespace Controllers;

use Exception;
use Services\LoginService;
use GivenCode\Abstracts\AbstractController;
use GivenCode\Exceptions\RequestException;
use Services\UserService;


class LoginController extends AbstractController
{

    private LoginService $loginService;
    private UserService $userService;

    public function __construct()
    {
        parent::__construct();
        $this->loginService = new LoginService();
        $this->userService = new UserService();
    }

    /**
     * @throws RequestException
     */
    public function get(): void
    {
        // Voluntary exception throw: no GET operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }

    /**
     * @throws Exception
     */
    public function post(): void
    {
        try {
            $username = $_POST["username"] ?? null;
            $password = $_POST["password"] ?? null;

            if (empty($username) || empty($password)) {
                throw new RequestException("Username and password are required.", 400);
            }

            $user = $this->userService->getUserByUsername($username);

            if (!$user) {
                throw new Exception("User not found.", 404);
            }

            $hashedPassword = $user->getPassword();

            // Compare plain text password with hashed password
            if ($password !== $hashedPassword) {
                // Passwords don't match
                throw new Exception("Incorrect password.", 401);
            }

            $userId = $user->getId();

            // Call the doLogin method with the user ID
            $this->loginService->doLogin($userId);

            $response = [
                "navigateTo" => WEB_ROOT_DIR
            ];
            if (!empty($_REQUEST["from"])) {
                $response["navigateTo"] = $_REQUEST["from"];
            }
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($response);
            exit();
        } catch (Exception $excep) {
            $code = $excep->getCode();
            if (!is_int($code)) {
                $code = 500;
            }
            throw new Exception("Failure to log user in.", $code, $excep);
        }
    }


    /**
     * @throws RequestException
     */
    public function put(): void
    {
        // Voluntary exception throw: no PUT operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }

    public function delete(): void
    {
        /*
         * NOTE: I use the DELETE method to trigger the logout
         */

        $this->loginService->doLogout();
        $response = [
            "navigateTo" => WEB_ROOT_DIR . "pages/login"
        ];
        if (!empty($_REQUEST["from"])) {
            $response["navigateTo"] = $_REQUEST["from"];
        }
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($response);
        exit();
    }
}