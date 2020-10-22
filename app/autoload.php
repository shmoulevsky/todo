<?
    spl_autoload_register(function($class) {
        
        if (file_exists(APP_DIR.'Classes/' . $class . '.php')) {
            require_once APP_DIR.'Classes/' . $class . '.php';
         }
         elseif (file_exists(APP_DIR.'Controller/' . $class . '.php')) {
            require_once APP_DIR.'Controller/' .$class . '.php';
         }
         elseif (file_exists( APP_DIR.'Model/' .$class . '.php')) {
            require_once APP_DIR.'Model/' .$class . '.php';
         }

    });
    require_once $_SERVER['DOCUMENT_ROOT'].'/config/routes.php';

?>