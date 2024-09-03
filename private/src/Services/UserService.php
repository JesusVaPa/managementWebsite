<?php
declare(strict_types=1);

namespace Services;

use Exception;
use DAOs\UserDAO;
use DTOs\UserDTO;
use GivenCode\Abstracts\IService;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use GivenCode\Services\DBConnectionService;

class UserService implements IService
{

    private UserDAO $dao;

    public function __construct()
    {
        $this->dao = new UserDAO();
    }

    /**
     * TODO: Function documentation
     *
     * @return UserDTO[]
     * @throws RuntimeException
     *
     */
    public function getAllUsers(): array
    {
        return $this->dao->getAll();
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserById(int $id): ?UserDTO
    {
        $user = $this->dao->getById($id);
        $user?->loadGroups();
        return $user;
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserByUsername(string $username): ?UserDTO
    {
        $user = $this->dao->getByUsername($username);
        $user?->loadGroups();
        return $user;
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function authenticateUser(string $username, string $password): ?UserDTO
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            // User not found
            return null;
        }

        if ($password != $user->getPassword()) {
            // Incorrect password
            return null;
        }

        // User authenticated
        return $user;
    }

    /**
     * @throws RuntimeException
     */
    public function createUser(string $username, string $password, string $email): UserDTO
    {
        try {
            $user = UserDTO::fromValues($username, $password, $email);
            return $this->dao->insert($user);

        } catch (Exception $excep) {
            throw new RuntimeException("Failure to create user [$username].", $excep->getCode(), $excep);
        }
    }

    /**
     * @throws RuntimeException
     */
    public function updateUser(int $id, string $username, string $password, string $email): UserDTO
    {
        try {
            $user = $this->dao->getById($id);
            if (is_null($user)) {
                throw new Exception("User id# [$id] not found in the database.");
            }
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setEmail($email);
            $result = $this->dao->update($user);
            return $result;

        } catch (Exception $excep) {
            throw new RuntimeException("Failure to update user id# [$id].", $excep->getCode(), $excep);
        }
    }

    /**
     * @throws RuntimeException
     */
    public function deleteUserById(int $id): void
    {
        $this->dao->deleteById($id);
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserGroups(UserDTO $user): array
    {
        return $this->getUserGroupsByUserId($user->getId());
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserGroupsByUserId(int $id): array
    {
        return $this->dao->getGroupsByUserId($id);
    }


}