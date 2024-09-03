<?php
declare(strict_types=1);


use DTOs\UserDTO;
use Services\UserService;
use Services\LoginService;

/*
if (!LoginService::isUserLoggedIn()) {
    LoginService::redirectToLogin();
}
*/

if (!LoginService::requireAdmin()) {
    if (!LoginService::isUserLoggedIn()) {
        LoginService::redirectToLogin();
    } else {
        (new LoginService())->doLogout();
        LoginService::redirectToLogin();
    }
}

$user_service = new UserService();
$all_users = $user_service->getAllUsers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "standard.css" ?>">
    <script type="text/javascript">

        const API_USER_URL = "<?= WEB_ROOT_DIR . "api/users" ?>";

    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "page.users.js" ?>" defer></script>
</head>
<body>
<header id="header">
    <?php
    include "standard.page.header.php";
    ?>
</header>
<main id="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">User Management</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 row align-items-end align-items-md-center justify-content-center justify-content-md-end">
                <label class="col-12 text-start text-md-end align-items-md-center"
                       for="user-selector">Select a user:</label>
            </div>
            <div class="col-12 col-md-4 row justify-content-center">
                <select id="user-selector" class="">
                    <option value="" selected disabled>Select one...</option>

                    <?php
                    foreach ($all_users as $instance) {

                        echo("<option class='" . "' value='" . $instance->getId() . "'>" . $instance->getUsername() . "</option>");
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-4 row justify-content-center justify-content-md-start py-2 py-md-0 px-4">
                <button id="view-instance-button"
                        class="btn btn-primary col-9 col-sm-5 col-md-9 col-lg-7 text-uppercase"
                        type="button">Load user
                </button>
            </div>
        </div>
        <div class="row">

        </div>
        <div class="error-display hidden">
            <h1 id="error-class" class="col-12 error-text"></h1>
            <h3 id="error-message" class="col-12 error-text"></h3>
            <div id="error-previous" class="col-12"></div>
            <pre id="error-stacktrace" class="col-12"></pre>
        </div>
        <br/>
        <div class="container">
            <form id="user-form" class="row">
                <div class="col-12">
                    <label class="form-label" for="user-id">Id: </label>
                    <input id="user-id" class="form-control form-control-sm" type="number" name="id" readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="user-username">Username:</label>
                    <input id="user-username" class="form-control" type="text" name="username"
                           maxlength="<?= UserDTO::USERNAME_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="user-password">Password:</label>
                    <input id="user-password" class="form-control" type="password" name="password"
                           maxlength="<?= UserDTO::PASSWORD_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="user-email">Email:</label>
                    <input id="user-email" class="form-control" type="text" name="email"
                           maxlength="<?= UserDTO::EMAIL_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="user-date-created">Date created: </label>
                    <input id="user-date-created" class="form-control form-control-sm" type="datetime-local"
                           name="dateCreated"
                           readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="user-date-last-modified">Date last modified: </label>
                    <input id="user-date-last-modified" class="form-control form-control-sm" type="datetime-local"
                           name="dateLastModified"
                           readonly disabled>
                </div>
            </form>
            <div class="col-12 d-flex flex-wrap justify-content-around button-row">
                <button id="create-button" type="button"
                        class="btn btn-primary col-12 col-md-2 my-1 my-md-0 text-uppercase">Create
                </button>
                <button id="clear-button" type="button" class="btn btn-info col-12 col-md-2 my-1 my-md-0 text-uppercase"
                        disabled>Clear Form
                </button>
                <button id="update-button" type="button"
                        class="btn btn-success col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Update
                </button>
                <button id="delete-button" type="button"
                        class="btn btn-danger col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Delete
                </button>
            </div>
        </div>
    </div>
</main>
<footer id="footer">
    <?php
    include "standard.page.footer.php";
    ?>
</footer>
</body>
</html>