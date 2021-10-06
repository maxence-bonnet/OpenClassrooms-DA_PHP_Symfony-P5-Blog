<?php

namespace App\Src\Controller\ErrorController;

use App\Src\Controller\Controller;
use Exception;

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

    public function errorServer(Exception $e)
    {
        $data['message'] = $e->getMessage();
        return $this->view->renderTwig('error500',$data);
    }
}