<?php
declare(strict_types=1);

namespace Controllers;

use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use Services\GroupService;
use Services\LoginService;
use GivenCode\Abstracts\AbstractController;
use GivenCode\Exceptions\RequestException;
use GivenCode\Services\DBConnectionService;

class GroupController extends AbstractController
{

    private GroupService $groupService;

    public function __construct()
    {
        parent::__construct();
        $this->groupService = new GroupService();
    }

    /**
     * @throws RuntimeException
     * @throws RequestException
     */
    public function get(): void
    {
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }

        if (empty($_REQUEST["groupId"])) {
            throw new RequestException("Bad request: required parameter [group] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["groupId"])) {
            throw new RequestException("Bad request: parameter [groupId] value [" . $_REQUEST["groupId"] .
                "] is not numeric.", 400);
        }
        $int_id = (int)$_REQUEST["groupId"];
        $instance = $this->groupService->getById($int_id);
        $instance->loadUsers();
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     * @throws RequestException
     */
    public function post(): void
    {
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        if (empty($_REQUEST["name"])) {
            throw new RequestException("Bad request: required parameter [name] not found in the request.", 400);
        }

        $connection = DBConnectionService::getConnection();
        if (!$connection->inTransaction()) {
            $connection->beginTransaction();
        }

        try {

            // create the group first
            $instance = $this->groupService->create($_REQUEST["name"], $_REQUEST["description"]);

            // then create the group-user associations
            if (!empty($_REQUEST["users"]) || is_array($_REQUEST["users"])) {

                // create the selected associations
                foreach ($_REQUEST["users"] as $userId => $is_checked) {
                    // only if checkbox value was checked.
                    // NOTE: unchecked checkbox pass the value 'false' as a string ao they still exist in the request
                    // and make the following == "true" check necessary.
                    if (strtolower($is_checked) == "true") {
                        $int_userId = (int)$userId;
                        $this->groupService->associateGroupWithUser($instance->getId(), $int_userId);
                    }
                }
            }

            // load the created associations
            $instance->loadUsers();
            // commit all DB operations
            $connection->commit();

            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($instance->toArray());

        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }
    }

    /**
     * @throws ValidationException
     * @throws RequestException
     * @throws RuntimeException
     */
    public function put(): void
    {

        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }

        $raw_request_string = file_get_contents("php://input");
        parse_str($raw_request_string, $_REQUEST);


        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: invalid parameter [id] value: non-numeric value found [" .
                $_REQUEST["id"] . "].", 400);
        }
        if (empty($_REQUEST["name"])) {
            throw new RequestException("Bad request: required parameter [name] not found in the request.", 400);
        }

        $int_group_id = (int)$_REQUEST["id"];

        $connection = DBConnectionService::getConnection();
        If (!$connection->inTransaction()) {
            $connection->beginTransaction();
        }

        try {

            if (!empty($_REQUEST["users"]) || is_array($_REQUEST["users"])) {
                // delete all previous user associations for the group
                $this->groupService->deleteAllGroupUserAssociationsForGroupId($int_group_id);

                // re-create the selected associations
                foreach ($_REQUEST["users"] as $userId => $is_checked) {
                    // only if checkbox value was checked.
                    // NOTE: unchecked checkbox pass the value 'false' as a string ao they still exist in the request
                    // and make the following == "true" check necessary.
                    if (strtolower($is_checked) == "true") {
                        $int_userId = (int)$userId;
                        $this->groupService->associateGroupWithUser($int_group_id, $int_userId);
                    }
                }
            }

            $instance = $this->groupService->update($int_group_id, $_REQUEST["name"], $_REQUEST["description"]);
            $instance->loadUsers();
            $connection->commit();

            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($instance->toArray());

        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }


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

        $raw_request_string = file_get_contents("php://input");
        parse_str($raw_request_string, $_REQUEST);


        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: invalid parameter [id] value: non-numeric value found [" .
                $_REQUEST["id"] . "].", 400);
        }

        $int_group_id = (int)$_REQUEST["id"];

        $connection = DBConnectionService::getConnection();
        If (!$connection->inTransaction()) {
            $connection->beginTransaction();
        }

        try {
            // I delete the group-user associations first then delete the group itself.
            // I have to do this in that order in the delete operation because the foreign key checks might block me
            // from deleting a group that still has existing associations (ON DELETE RESTRICT foreign key option).

            // delete all user associations for the group
            $this->groupService->deleteAllGroupUserAssociationsForGroupId($int_group_id);

            // delete the group itself
            $this->groupService->delete($int_group_id);

            // commit transaction operations
            $connection->commit();

            header("Content-Type: application/json;charset=UTF-8");
            // 204 NO CONTENT response code
            http_response_code(204);

        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }
    }
}