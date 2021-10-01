<?php

namespace App\src\controller\backcontroller;

use App\src\controller\Controller;

class BackController extends Controller
{
    protected function checkAdmin()
    {
        $this->checkLoggedIn();

        if(!($this->session->get('role') === 'admin')){
            $this->session->addMessage('danger', 'Vous n\'avez pas le droit d\'accéder à cette page');
            $this->http->redirect('?');
        }
    }
}