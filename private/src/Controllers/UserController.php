<?php
declare(strict_types=1);

namespace Controllers;

use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use GivenCode\Services\DBConnectionService;
use Services\UserService;
use Services\LoginService;
use GivenCode\Abstracts\AbstractController;
use GivenCode\Exceptions\RequestException;

class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     * @throws RequestException
     */
    public function get(): void
    {

        if (!LoginService::isUserLoggedIn()) {
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }

        if (empty($_REQUEST["userId"])) {
            throw new RequestException("Bad request: required parameter [userId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["userId"])) {
            throw new RequestException("Bad request: parameter [userId] value [" . $_REQUEST["userId"] .
                "] is not numeric.", 400);
        }
        $int_id = (int)$_REQUEST["userId"];
        $instance = $this->userService->getUserById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }

    /**
     * @throws RuntimeException
     * @throws RequestException
     */
    public function post(): void
    {

        if (!LoginService::isUserLoggedIn()) {
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        if (empty($_REQUEST["username"])) {
            throw new RequestException("Bad request: required parameter [username] not found in the request.", 400);
        }
        if (empty($_REQUEST["password"])) {
            throw new RequestException("Bad request: required parameter [password] not found in the request.", 400);
        }
        if (empty($_REQUEST["email"])) {
            throw new RequestException("Bad request: required parameter [email] not found in the request.", 400);
        }

        $instance = $this->userService->createUser($_REQUEST["username"], $_REQUEST["password"], $_REQUEST["email"]);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     * @throws RequestException
     */
    public function put(): void
    {

        if (!LoginService::isUserLoggedIn()) {
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }

        $raw_request_string = file_get_contents("php://input");
        parse_str($raw_request_string, $_REQUEST);

        if (empty($_REQUEST["userId"])) {
            throw new RequestException("Bad request: required parameter [userId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["userId"])) {
            throw new RequestException("Bad request: invalid parameter [userId] value: non-numeric value found [" .
                $_REQUEST["userId"] . "].", 400);
        }

        if (empty($_REQUEST["username"])) {
            throw new RequestException("Bad request: required parameter [username] not found in the request.", 400);
        }
        $int_user_id = (int)$_REQUEST["userId"];

        $connection = DBConnectionService::getConnection();
        if (!$connection->inTransaction()) {
            $connection->beginTransaction();
        }
        try {

            $instance = $this->userService->updateUser($int_user_id, $_REQUEST["username"], $_REQUEST["password"],
                $_REQUEST["email"]);
            $instance->loadGroups();
            $connection->commit();

        } catch (\Exception $inner_excep) {
            $connection->rollBack();
            throw $inner_excep;
        }

        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }

    /**
     * @throws RuntimeException
     * @throws RequestException
     */
    public function delete(): void
    {

        if (!LoginService::isUserLoggedIn()) {
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }

        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);

        if (empty($_REQUEST["userId"])) {
            throw new RequestException("Bad request: required parameter [userId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["userId"])) {
            throw new RequestException("Bad request: parameter [userId] value [" . $_REQUEST["userId"] .
                "] is not numeric.", 400);
        }
        $int_user_id = (int)$_REQUEST["userId"];
        $this->userService->deleteUserById($int_user_id);
        header("Content-Type: application/json;charset=UTF-8");
        http_response_code(204);
    }
}