<?php
declare(strict_types=1);


?>
<div class="container">
    <div class="flex-column justify-content-start">
        <div class="row links-container">
            <div class="flex-column links-block">
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR . "pages/users" ?>">User management page</a>
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR . "pages/groups" ?>">Group management page</a>
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR . "pages/permissions" ?>">Permissions management
                    page</a>
            </div>
        </div>
        <div class="row copyright-container">
            <div class="flex-column">
                <span class="copyright-notice">Copyright (c) <?= (new DateTime())->format('Y') ?> Jesus Angel Vazquez Padilla - All rights reserved.</span>
            </div>
        </div>
    </div>
</div>
