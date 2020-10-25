<?php
namespace app\requests;

use core\Request;

class LoginRequest extends Request
{

    public function __construct()
    {
        $form = array(
            'username|Usuario' => 'requerido',
            'email|Correo' => 'requerido|email'
        );
        self::$form = $form;
    }

}
