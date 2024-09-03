<?php
declare(strict_types=1);

namespace DTOs;

use DateTime;
use GivenCode\Exceptions\ValidationException;

class PermissionDTO
{

    public const TABLE_NAME = "permissions";

    private int $permission_id;
    private string $permission_identifier;
    private string $permission_name;
    private string $permission_description;
    private ?DateTime $created_at = null;
    private ?DateTime $modified_at = null;

    public function __construct()
    {
    }

    public static function fromValues(string $permission_identifier, string $permission_name, string $permission_description): PermissionDTO
    {
        $instance = new PermissionDTO();
        $instance->setIdentifier($permission_identifier);
        $instance->setName($permission_name);
        $instance->setDescription($permission_description);
        return $instance;
    }

    /**
     * @throws ValidationException
     */
    public static function fromDbArray(array $dbArray): PermissionDTO
    {
        self::validateDbArray($dbArray);
        $instance = new PermissionDTO();
        $instance->setId((int)$dbArray["permission_id"]);
        $instance->setIdentifier($dbArray["permission_identifier"]);
        $instance->setName($dbArray["permission_name"]);
        $instance->setDescription($dbArray["permission_description"]);
        $instance->setDateCreated(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["created_at"]));
        if (!empty($dbArray["modified_at"])) {
            $instance->setDateLastModified(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["modified_at"]));
        }
        return $instance;
    }

    public function getId(): int
    {
        return $this->permission_id;
    }

    /**
     * @throws ValidationException
     */
    public function setId(int $permission_id): void
    {
        if ($permission_id <= 0) {
            throw new ValidationException("Invalid value for PermissionDTO [permission_id]: must be a positive integer > 0.");
        }
        $this->permission_id = $permission_id;
    }

    public function getIdentifier(): string
    {
        return $this->permission_identifier;
    }

    public function setIdentifier(string $permission_identifier): void
    {
        $this->permission_identifier = $permission_identifier;
    }

    public function getName(): string
    {
        return $this->permission_name;
    }

    public function setName(string $permission_name): void
    {
        $this->permission_name = $permission_name;
    }

    public function getDescription(): string
    {
        return $this->permission_description;
    }

    public function setDescription(string $permission_description): void
    {
        $this->permission_description = $permission_description;
    }

    public function getDateCreated(): DateTime
    {
        return $this->created_at;
    }

    public function setDateCreated(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getDateLastModified(): DateTime
    {
        return $this->modified_at;
    }

    public function setDateLastModified(?DateTime $modified_at): void
    {
        $this->modified_at = $modified_at;
    }

    /**
     * @throws ValidationException
     */
    private static function validateDbArray(array $dbArray): void
    {
        if (empty($dbArray["permission_id"])) {
            throw new ValidationException("Record array does not contain an [permission_id] field. Check column names.");
        }
        if (!is_numeric($dbArray["permission_id"])) {
            throw new ValidationException("Record array [permission_id] field is not numeric. Check column types.");
        }
        if (empty($dbArray["permission_name"])) {
            throw new ValidationException("Record array does not contain an [permission_name] field. Check column names.");
        }
        if (empty($dbArray["permission_identifier"])) {
            throw new ValidationException("Record array does not contain an [permission_identifier] field. Check column names.");
        }
        if (empty($dbArray["permission_description"])) {
            throw new ValidationException("Record array does not contain an [permission_description] field. Check column names.");
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
        // ID must not be set
        if (!empty($this->permission_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // permission_name is required
        if (empty($this->permission_name)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permission_name value not set.");
            }
            return false;
        }
        // permission_identifier is required
        if (empty($this->permission_identifier)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permission_identifier value not set.");
            }
            return false;
        }
        if (empty($this->permission_description)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permission_description value not set.");
            }
            return false;
        }
        if (!is_null($this->created_at)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: created_at value already set.");
            }
            return false;
        }
        if (!is_null($this->modified_at)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: modified_at value already set.");
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
        // ID is required
        if (empty($this->permission_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB update: ID value is not set.");
            }
            return false;
        }
        // permission_name is required
        if (empty($this->permission_name)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB update: permission_name value not set.");
            }
            return false;
        }
        // permission_identifier is required
        if (empty($this->permission_identifier)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB update: permission_identifier value not set.");
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
        // ID is required
        if (empty($this->permission_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        return true;
    }

    public function toArray(): array
    {
        $array = [
            "permissionId" => $this->getId(),
            "permission_name" => $this->getName(),
            "permission_description" => $this->getDescription(),
            "permission_identifier" => $this->getIdentifier(),
            "created_at" => $this->getDateCreated()?->format(HTML_DATETIME_FORMAT),
            "modified_at" => $this->getDateLastModified()?->format(HTML_DATETIME_FORMAT),
        ];
        return $array;
    }

}
