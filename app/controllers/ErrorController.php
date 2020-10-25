<?php
use core\Response;

class ErrorController
{

    public function error404()
    {

        Response::render('errors/404');

    }

}
