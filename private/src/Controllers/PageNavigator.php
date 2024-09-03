<?php
declare(strict_types=1);

namespace Controllers;

use GivenCode\Abstracts\IService;

class PageNavigator implements IService
{


    public static function loginPage(): void
    {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "page.login.php";
    }

    public static function groupsManagementPage(): void
    {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "page.management.groups.php";
    }

    public static function usersManagementPage(): void
    {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "page.management.users.php";
    }

    public static function permissionsManagementPage(): void
    {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "page.management.permissions.php";
    }
}