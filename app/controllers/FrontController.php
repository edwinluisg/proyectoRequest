<?php
use core\Response;
use app\requests\LoginRequest;


class FrontController
{

    public function index()
    {
        
      Response::render('index');

    }

    public function validar()
    {
        $request = new LoginRequest;
        if ($request::validar()) {
            Response::json($request::$errorsCont);
        } else {
            Response::json($request::$errors);
        }
    }

}
