<?php
declare(strict_types=1);

namespace Services;

use Exception;
use DAOs\PermissionDAO;
use DTOs\PermissionDTO;
use GivenCode\Abstracts\IService;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use GivenCode\Services\DBConnectionService;

class PermissionService implements IService
{

    private PermissionDAO $dao;

    public function __construct()
    {
        $this->dao = new PermissionDAO();
    }

    /**
     * TODO: Function documentation
     *
     * @return PermissionDTO[]
     * @throws RuntimeException
     *
     */
    public function getAllPermissions(): array
    {
        return $this->dao->getAll();
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getPermissionById(int $id): ?PermissionDTO
    {
        $permission = $this->dao->getById($id);
        return $permission;
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getPermissionByName(string $name): ?PermissionDTO
    {
        $permission = $this->dao->getByName($name);
        return $permission;
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function authenticatePermission(string $name): ?PermissionDTO
    {
        $permission = $this->getPermissionByName($name);

        if (!$permission) {
            return null;
        }
        return $permission;
    }

    /**
     * @throws RuntimeException
     */
    public function createPermission(string $identifier, string $name, string $description): PermissionDTO
    {
        try {
            $permission = PermissionDTO::fromValues($identifier, $name, $description);
            return $this->dao->insert($permission);

        } catch (Exception $excep) {
            throw new RuntimeException("Failure to create permission [$name].", $excep->getCode(), $excep);
        }
    }

    /**
     * @throws RuntimeException
     */
    public function updatePermission(int $id, string $identifier, string $name, string $description): PermissionDTO
    {
        try {
            $permission = $this->dao->getById($id);
            if (is_null($permission)) {
                throw new Exception("Permission id# [$id] not found in the database.");
            }
            $permission->setName($name);
            $permission->setIdentifier($identifier);
            $permission->setDescription($description);
            $result = $this->dao->update($permission);
            return $result;
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to update user id# [$id].", $excep->getCode(), $excep);
        }
    }

    public function deletePermissionById(int $id): void
    {
        $this->dao->deleteById($id);
    }

}