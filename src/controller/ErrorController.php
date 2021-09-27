<?php

namespace App\src\controller;

class ErrorController extends Controller
{
    public function errorForbidden()
    {
        return $this->view->renderTwig('error403');
    }

    public function errorNotFound()
    {
        return $this->view->renderTwig('error404');
    }

    public function errorServer()
    {
        return $this->view->renderTwig('error500');
    }
}