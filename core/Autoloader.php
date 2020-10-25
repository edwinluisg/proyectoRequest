<?php

class Autoloader
{

    public function __construct()
    {

        $this->loadApp();
        $this->loadAppClasses();

    }

    private function loadApp()
    {

        require_once CORE_PATH . "App.php";

    }

    private function loadAppClasses()
    {

        spl_autoload_register(function ($className) {
            $class = substr($className, 0, 3);
            if ($class == 'cor' or $class == 'app') {
                require_once preg_replace("/\\\\/", "/", $className) . ".php";
            }
        });

    }

}

new Autoloader();
