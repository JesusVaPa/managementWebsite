<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project GroupService.php
 * 
 * @user Marc-Eric Boury (MEbou)
 * @since 2024-04-03
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Services;

use DAOs\UserGroupDAO;
use DAOs\GroupDAO;
use DTOs\UserDTO;
use DTOs\GroupDTO;
use GivenCode\Abstracts\IService;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @user Marc-Eric Boury
 * @since  2024-04-03
 */
class GroupService implements IService
{

    private GroupDAO $dao;
    private UserGroupDAO $userGroupDao;

    public function __construct()
    {
        $this->dao = new GroupDAO();
        $this->userGroupDao = new UserGroupDAO();
    }

    /**
     * TODO: Function documentation
     *
     * @return GroupDTO[]
     */
    public function getAllGroups(): array
    {
        return $this->dao->getAll();
    }

    public function getById(int $id): ?GroupDTO
    {
        return $this->dao->getById($id);
    }

    /**
     * @throws ValidationException
     */
    public function create(string $name, ?string $group_description = null): GroupDTO
    {
        $instance = GroupDTO::fromValues($name, $group_description);
        return $this->dao->insert($instance);
    }

    /**
     * @throws ValidationException
     */
    public function update(int $id, string $name, ?string $group_description = null): GroupDTO
    {
        // No transaction this time, contrary to the Example stack
        $instance = $this->dao->getById($id);
        $instance->setName($name);
        $instance->setDescription($group_description);
        return $this->dao->update($instance);
    }

    public function delete(int $id): void
    {
        $this->dao->deleteById($id);
    }

    /**
     * TODO: Function documentation
     *
     * @param GroupDTO $group
     * @return UserDTO[]
     *
     * @user Marc-Eric Boury
     * @throws RuntimeException
     * @since  2024-04-04
     */
    public function getGroupUsers(GroupDTO $group): array
    {
        return $this->getGroupUsersByGroupId($group->getId());
    }

    /**
     * TODO: Function documentation
     *
     * @param int $id
     * @return UserDTO[]
     * @throws RuntimeException
     */
    public function getGroupUsersByGroupId(int $id): array
    {
        return $this->dao->getUsersByGroupId($id);
    }

    public function deleteAllGroupUserAssociationsForGroup(GroupDTO $group): void
    {
        $this->deleteAllGroupUserAssociationsForGroupId($group->getId());
    }

    /**
     * @throws RuntimeException
     */
    public function deleteAllGroupUserAssociationsForGroupId(int $groupId): void
    {
        $this->userGroupDao->deleteAllByGroupId($groupId);
    }

    /**
     * @throws RuntimeException
     */
    public function associateGroupWithUser(int $groupId, int $userId): void
    {
        $this->userGroupDao->createForUserAndGroup($userId, $groupId);
    }

}