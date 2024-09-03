<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UserGroupDAO.php
 * 
 * @user Marc-Eric Boury (MEbou)
 * @since 2024-04-04
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace DAOs;

use GivenCode\Exceptions\RuntimeException;
use PDO;
use GivenCode\Services\DBConnectionService;

class UserGroupDAO
{
    public const TABLE_NAME = "user_group";
    private const CREATE_QUERY = "INSERT INTO " . self::TABLE_NAME .
    " (`user_id`, `group_id`) VALUES (:userId, :groupId);";

    public function __construct()
    {
    }

    /**
     * @throws RuntimeException
     */
    public function createForUserAndGroup(int $userId, int $groupId): void
    {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        $statement->bindValue(":groupId", $groupId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @throws RuntimeException
     */
    public function createManyForUser(int $userId, array $groupIds): void
    {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        foreach ($groupIds as $group_id) {
            $statement->bindValue(":groupId", $group_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }

    /**
     * @throws RuntimeException
     */
    public function createManyForGroup(int $groupId, array $userIds): void
    {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":groupId", $groupId, PDO::PARAM_INT);
        foreach ($userIds as $userId) {
            $statement->bindParam(":userId", $userId, PDO::PARAM_INT);
            $statement->execute();
        }
    }

    /**
     * @throws RuntimeException
     */
    public function deleteAllByGroupId(int $groupId): void
    {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `group_id` = :groupId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":groupId", $groupId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @throws RuntimeException
     */
    public function deleteAllByUserId(int $userId): void
    {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `user_id` = :userId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        $statement->execute();
    }

}