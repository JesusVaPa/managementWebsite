<?php
declare(strict_types=1);

namespace DTOs;

use DateTime;
use Exception;
use DAOs\UserGroupDAO;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;

class UserGroupDTO
{

    /**
     * The database table name for this entity type.
     * @const
     */
    public const TABLE_NAME = "user_group";
    private int $user_id;
    private int $group_id;

    /**
     * TODO: Property documentation
     *
     * @var GroupDTO[]
     * @var UserDTO[]
     */
    private array $userGroups = [];

    public function __construct()
    {
    }

    /**
     * TODO: Function documentation
     *
     * @param int $user_id
     * @param int $group_id
     * @return UserGroupDTO
     * @throws ValidationException
     */
    public static function fromValues(int $user_id, int $group_id): UserGroupDTO
    {
        $instance = new UserGroupDTO();
        $instance->setUserId($user_id);
        $instance->setGroupId($group_id);
        return $instance;
    }

    /**
     * TODO: Function documentation
     *
     * @param array $dbArray
     * @return UserGroupDTO
     * @throws ValidationException
     */
    public static function fromDbArray(array $dbArray): UserGroupDTO
    {
        self::validateDbArray($dbArray);
        $instance = new UserGroupDTO();
        $instance->setUserId((int)$dbArray["user_id"]);
        $instance->setGroupId((int)$dbArray["group_id"]);
        return $instance;
    }

    public function getDatabaseTableName(): string
    {
        return self::TABLE_NAME;
    }

    // <editor-fold defaultstate="collapsed" desc="VALIDATION METHODS">

    /**
     * @throws ValidationException
     */
    private static function validateDbArray(array $dbArray): void
    {
        if (empty($dbArray["id"])) {
            throw new ValidationException("Record array does not contain an [id] field. Check column names.");
        }
        if (!is_numeric($dbArray["id"])) {
            throw new ValidationException("Record array [id] field is not numeric. Check column types.");
        }
        if (empty($dbArray["username"])) {
            throw new ValidationException("Record array does not contain an [username] field. Check column names.");
        }
        if (empty($dbArray["password"])) {
            throw new ValidationException("Record array does not contain an [password] field. Check column names.");
        }
        if (empty($dbArray["email"])) {
            throw new ValidationException("Record array does not contain an [email] field. Check column names.");
        }
        if (empty($dbArray["created_at"])) {
            throw new ValidationException("Record array does not contain an [created_at] field. Check column names.");
        }
        if (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["created_at"]) === false) {
            throw new ValidationException("Failed to parse [created_at] field as DateTime. Check column types.");
        }
        if (!empty($dbArray["modified_at"]) &&
            (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["modified_at"]) === false)
        ) {
            throw new ValidationException("Failed to parse [modified_at] field as DateTime. Check column types.");
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateForDbCreation(bool $optThrowExceptions = true): bool
    {
        if (!empty($this->user_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        if (!empty($this->group_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function validateForDbUpdate(bool $optThrowExceptions = true): bool
    {
        if (empty($this->user_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB update: ID value is not set.");
            }
            return false;
        }
        if (empty($this->group_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB update: ID value is not set.");
            }
            return false;
        }
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function validateForDbDelete(bool $optThrowExceptions = true): bool
    {
        if (empty($this->user_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        if (empty($this->group_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        return true;
    }

    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="GETTERS AND SETTERS">

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $id
     * @throws ValidationException
     */
    public function setUserId(int $id): void
    {
        if ($id <= 0) {
            throw new ValidationException("Invalid value for UserGroupDTO [UserId]: must be a positive integer > 0.");
        }
        $this->user_id = $id;
    }

    /**
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->group_id;
    }

    /**
     * @param int $id
     * @return void
     * @throws ValidationException
     */
    public function setGroupId(int $id): void
    {
        if ($id <= 0) {
            throw new ValidationException("Invalid value for UserGroupDTO [GroupId]: must be a positive integer > 0.");
        }
        $this->group_id = $id;
    }

    /**
     * TODO: Function documentation
     *
     * @param bool $forceReload [default=false] If <code>true</code>, forces the reload of the group records from the database.
     * @return array
     * @throws RuntimeException
     */


    // </editor-fold>
    public function toArray(): array
    {
        $array = [
            "user_id" => $this->getUserId(),
            "group_id" => $this->getGroupId()
        ];
        foreach ($this->userGroups as $userGroup) {
            $array["userGroups"][$userGroup->getId()] = $userGroup->toArray();
        }
        return $array;
    }


}