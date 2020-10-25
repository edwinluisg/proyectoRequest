<?php
namespace app\requests;

use core\Request;

class LoginRequest extends Request
{

    public function __construct()
    {
        $form = array(
            'username|Usuario' => 'required',
            'email|Correo' => 'required|email'
        );
        self::$form = $form;
    }

}
