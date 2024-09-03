<?php
declare(strict_types=1);


require_once "constants.php";

/**
 * Autoloader function used to load classes defined inside the 'src'
 * directory. PHP will use this function to attempt to load class definition files
 * when a class symbol with no loaded definition is used.
 *
 * @param string $classFQN The class' fully qualified name
 * @return void
 */
function project_autoloader(string $classFQN): void
{
    $path_from_fqn = str_replace("\\", NAMESPACE_PATH_SEPARATOR, $classFQN);
    require_once PRJ_SRC_DIR . $path_from_fqn . ".php";
}

spl_autoload_register("project_autoloader");

