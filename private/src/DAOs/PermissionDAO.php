<?php
declare(strict_types=1);

namespace DAOs;

use PDO;
use DTOs\PermissionDTO;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use GivenCode\Services\DBConnectionService;

class PermissionDAO
{

    /**
     * @return PermissionDTO[]
     * @throws RuntimeException
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM " . PermissionDTO::TABLE_NAME . ";";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);

        $permissions = [];
        foreach ($result_set as $result) {
            $permissions[] = PermissionDTO::fromDbArray($result);
        }

        return $permissions;

    }

    /**
     * @param int $id
     * @return PermissionDTO|null
     * @throws RuntimeException
     */
    public function getById(int $id): ?PermissionDTO
    {
        $query = "SELECT * FROM " . PermissionDTO::TABLE_NAME . " WHERE permission_id = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        $permission_array = $statement->fetch(PDO::FETCH_ASSOC);
        return PermissionDTO::fromDbArray($permission_array);
    }

    /**
     * @param string $permissionIdentifier
     * @return PermissionDTO|null
     * @throws RuntimeException
     */
    public function getByIdentifier(string $identifier): ?PermissionDTO
    {
        $query = "SELECT * FROM " . PermissionDTO::TABLE_NAME . " WHERE permission_identifier = :identifier ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":identifier", $identifier, PDO::PARAM_STR);
        $statement->execute();
        $permission_array = $statement->fetch(PDO::FETCH_ASSOC);
        return PermissionDTO::fromDbArray($permission_array);
    }

    /**
     * @param PermissionDTO $permission
     * @return PermissionDTO
     * @throws ValidationException|RuntimeException
     */
    public function insert(PermissionDTO $permission): PermissionDTO
    {
        $permission->validateForDbCreation();
        $query =
            "INSERT INTO " . PermissionDTO::TABLE_NAME . " (`permission_identifier`, `permission_name`, `permission_description`) VALUES (:identifier, :name, :description);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":identifier", $permission->getIdentifier(), PDO::PARAM_STR);
        $statement->bindValue(":name", $permission->getName(), PDO::PARAM_STR);
        $statement->bindValue(":description", $permission->getDescription(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int)$connection->lastInsertId();
        return $this->getById($new_id);
    }

    /**
     * @param PermissionDTO $permission
     * @return PermissionDTO
     * @throws RuntimeException
     */
    public function update(PermissionDTO $permission): PermissionDTO
    {
        $permission->validateForDbUpdate();
        $query =
            "UPDATE " . PermissionDTO::TABLE_NAME .
            " SET `permission_identifier` = :identifier, `permission_name` = :name, `permission_description` = :description WHERE `permission_id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":identifier", $permission->getIdentifier(), PDO::PARAM_STR);
        $statement->bindValue(":name", $permission->getName(), PDO::PARAM_STR);
        $statement->bindValue(":description", $permission->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(":id", $permission->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $this->getById($permission->getId());
    }

    /**
     * @param PermissionDTO $permission
     * @return void
     * @throws RuntimeException
     */
    public function delete(PermissionDTO $permission): void
    {
        $permission->validateForDbDelete();
        $this->deleteById($permission->getId());
    }

    public function deleteById(int $permissionId): void
    {
        $query =
            "DELETE FROM `" . PermissionDTO::TABLE_NAME .
            "` WHERE `permission_id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $permissionId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getByName(string $name): PermissionDTO
    {
        $query = "SELECT * FROM " . PermissionDTO::TABLE_NAME . " WHERE permission_name = :name ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":name", $name, PDO::PARAM_STR);
        $statement->execute();
        $permission_array = $statement->fetch(PDO::FETCH_ASSOC);
        return PermissionDTO::fromDbArray($permission_array);
    }
}
