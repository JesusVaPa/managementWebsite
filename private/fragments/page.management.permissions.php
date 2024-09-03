<?php
declare(strict_types=1);


use DTOs\PermissionDTO;
use Services\PermissionService;
use Services\LoginService;

/*
if (!LoginService::isPermissionLoggedIn()) {
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

$permission_service = new PermissionService();
$all_permissions = $permission_service->getAllPermissions();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Permission Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "standard.css" ?>">
    <script type="text/javascript">

        const API_PERMISSION_URL = "<?= WEB_ROOT_DIR . "api/permissions" ?>";

    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "page.permissions.js" ?>" defer></script>
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
            <h3 class="fullwidth text-center">Permission Management</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 row align-items-end align-items-md-center justify-content-center justify-content-md-end">
                <label class="col-12 text-start text-md-end align-items-md-center"
                       for="permission-selector">Select a permission:</label>
            </div>
            <div class="col-12 col-md-4 row justify-content-center">
                <select id="permission-selector" class="">
                    <option value="" selected disabled>Select one...</option>

                    <?php
                    foreach ($all_permissions as $instance) {

                        echo("<option class='" . "' value='" . $instance->getId() . "'>" . $instance->getName() . "</option>");
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-4 row justify-content-center justify-content-md-start py-2 py-md-0 px-4">
                <button id="view-instance-button"
                        class="btn btn-primary col-9 col-sm-5 col-md-9 col-lg-7 text-uppercase"
                        type="button">Load permission
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
            <form id="permission-form" class="row">
                <div class="col-12">
                    <label class="form-label" for="permission-id">Id: </label>
                    <input id="permission-id" class="form-control form-control-sm" type="number" name="id" readonly
                           disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="permission-name">Name:</label>
                    <input id="permission-name" class="form-control" type="text" name="name" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="permission-identifier">Identifier:</label>
                    <input id="permission-identifier" class="form-control" type="text" name="identifier" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="permission-description">Description:</label>
                    <input id="permission-description" class="form-control" type="text" name="description" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="author-date-created">Date created: </label>
                    <input id="author-date-created" class="form-control form-control-sm" type="datetime-local"
                           name="dateCreated"
                           readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="author-date-last-modified">Date last modified: </label>
                    <input id="author-date-last-modified" class="form-control form-control-sm" type="datetime-local"
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