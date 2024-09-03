<?php
declare(strict_types=1);

namespace Controllers;

use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use Services\PermissionService;
use Services\LoginService;
use GivenCode\Abstracts\AbstractController;
use GivenCode\Exceptions\RequestException;

class PermissionController extends AbstractController
{

    private PermissionService $permissionService;

    public function __construct()
    {
        parent::__construct();
        $this->permissionService = new PermissionService();
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

        if (empty($_REQUEST["permissionId"])) {
            throw new RequestException("Bad request: required parameter [permissionId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["permissionId"])) {
            throw new RequestException("Bad request: parameter [permissionId] value [" . $_REQUEST["permissionId"] .
                "] is not numeric.", 400);
        }
        $int_id = (int)$_REQUEST["permissionId"];
        $instance = $this->permissionService->getPermissionById($int_id);
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

        if (empty($_REQUEST["permission_description"])) {
            throw new RequestException("Bad request: required parameter [description] not found in the request.", 400);
        }
        if (empty($_REQUEST["permission_identifier"])) {
            throw new RequestException("Bad request: required parameter [identifier] not found in the request.", 400);
        }
        if (empty($_REQUEST["permission_name"])) {
            throw new RequestException("Bad request: required parameter [name] not found in the request.", 400);
        }
        $instance = $this->permissionService->createPermission($_REQUEST["permission_identifier"], $_REQUEST["permission_name"], $_REQUEST["permission_description"]);
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

        if (empty($_REQUEST["permissionId"])) {
            throw new RequestException("Bad request: required parameter [permissionId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["permissionId"])) {
            throw new RequestException("Bad request: invalid parameter [permissionId] value: non-numeric value found [" .
                $_REQUEST["permissionId"] . "].", 400);
        }

        // Check if name is present in the request data
        if (empty($_REQUEST["permission_name"])) {
            throw new RequestException("Bad request: required parameter [name] not found in the request.", 400);
        }

        $int_id = $_REQUEST["permissionId"];

        $instance = $this->permissionService->updatePermission($int_id, $_REQUEST["permission_name"], $_REQUEST["permission_description"], $_REQUEST["permission_identifier"]);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }

    /**
     * @throws RuntimeException
     * @throws RequestException
     */
    public function delete(): void
    {

        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }

        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);

        if (empty($_REQUEST["permission_id"])) {
            throw new RequestException("Bad request: required parameter [permission_id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["permission_id"])) {
            throw new RequestException("Bad request: parameter [permission_id] value [" . $_REQUEST["permission_id"] .
                "] is not numeric.", 400);
        }
        $int_id = (int)$_REQUEST["permission_id"];
        $this->permissionService->deletePermissionById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        http_response_code(204);
    }
}