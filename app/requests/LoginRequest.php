<?php
namespace app\requests;

use core\Request;

class LoginRequest extends Request
{

    public function __construct()
    {
        $form = array(
            'username|Usuario' => 'required|integer',
            'email|Correo' => 'required|time'
        );
        self::$form = $form;
    }

}
