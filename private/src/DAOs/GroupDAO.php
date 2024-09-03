<?php /** @noinspection ALL */
declare(strict_types=1);

namespace DAOs;

use PDO;
use DTOs\UserDTO;
use DTOs\GroupDTO;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use GivenCode\Services\DBConnectionService;

class GroupDAO {
    
    public function __construct() {}
    
    
    /**
     * TODO: Function documentation
     *
     * @return GroupDTO[]
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getAll() : array {
        $query = "SELECT * FROM `" . GroupDTO::TABLE_NAME . "`;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $records_array = $statement->fetchAll(PDO::FETCH_ASSOC);
        $groups = [];
        foreach ($records_array as $record) {
            $groups[] = GroupDTO::fromDbArray($record);
        }
        return $groups;
    }
    
    public function getById(int $id) : ?GroupDTO {
        $query = "SELECT * FROM `" . GroupDTO::TABLE_NAME . "` WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        $record_array = $statement->fetch(PDO::FETCH_ASSOC);
        return GroupDTO::fromDbArray($record_array);
    }
    
    public function insert(GroupDTO $group) : GroupDTO {
        $group->validateForDbCreation();
        $query =
            "INSERT INTO `" . GroupDTO::TABLE_NAME .
            "` (`name`, `description`) VALUES (:name, :description);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":name", $group->getName(), PDO::PARAM_STR);
        if (!is_null($group->getDescription())) {
            $statement->bindValue(":description", $group->getDescription(), PDO::PARAM_STR);
        } else {
            $statement->bindValue(":description", $group->getDescription(), PDO::PARAM_NULL);
        }
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        return $this->getById($new_id);
    }
    
    public function update(GroupDTO $group) : GroupDTO {
        $group->validateForDbUpdate();
        $query =
            "UPDATE `" . GroupDTO::TABLE_NAME .
            "` SET `name` = :name, `description` = :description WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":name", $group->getName(), PDO::PARAM_STR);
        if (!is_null($group->getDescription())) {
            $statement->bindValue(":description", $group->getDescription(), PDO::PARAM_STR);
        } else {
            $statement->bindValue(":description", $group->getDescription(), PDO::PARAM_NULL);
        }
        $statement->bindValue(":id", $group->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $this->getById($group->getId());
    }
    
    public function delete(GroupDTO $group) : void {
        $group->validateForDbDelete();
        $this->deleteById($group->getId());
    }
    
    public function deleteById(int $groupId) : void {
        $query =
            "DELETE FROM `" . GroupDTO::TABLE_NAME .
            "` WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $groupId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * TODO: Function documentation
     *
     * @param GroupDTO $group
     * @return UserDTO[]
     *
     * @throws RuntimeException
     */
    public function getUsersByGroup(GroupDTO $group) : array {
        return $this->getUsersByGroupId($group->getId());
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int $id
     * @return UserDTO[]
     *
     * @throws RuntimeException
     * @user Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getUsersByGroupId(int $id) : array {
        $query = "SELECT a.* FROM " . UserDTO::TABLE_NAME . " a JOIN " . UserGroupDAO::TABLE_NAME .
            " ab ON a.id = ab.user_id JOIN " . GroupDTO::TABLE_NAME . " b ON ab.group_id = b.id WHERE b.id = :groupId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":groupId", $id, PDO::PARAM_INT);
        $statement->execute();

        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $user_array = [];
        foreach ($result_set as $user_record) {
            $user_array[] = UserDTO::fromDbArray($user_record);
        }
        return $user_array;
    }
    
}