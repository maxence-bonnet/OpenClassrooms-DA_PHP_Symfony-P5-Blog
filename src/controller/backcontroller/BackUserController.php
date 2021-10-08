<?php

namespace App\Src\Controller\BackController;

use App\Config\Parameter;
use App\Src\Controller\Controller;
use \Exception;

class BackUserController extends BackController
{
    public function updateUserRole(Parameter $get) : void
    {
        if ($get->get('userId') && $get->get('roleId') && $get->get('token')) {

            $this->checkToken((string)$get->get('token'));
                
            $roleId = (int)$get->get('roleId');
            $userId = (int)$get->get('userId');

            $rolesArray = [1,2,3,4];
            $user = $this->userDAO->getUser($userId);
            if ($user) {
                if (((int)$user->getRoleId() !== 1) && ((int)$user->getRoleId() !== $get->get('roleId')) && in_array($roleId, $rolesArray)) {

                    $this->userDAO->updateUserRole($userId,$roleId);

                    if ($roleId === 1) {
                        $roleName = "Administrateur";
                    } elseif ($roleId === 2) {
                        $roleName = "Utilisateur";
                    } elseif ($roleId === 3) {
                        $roleName = "Éditeur";
                    } elseif ($roleId === 4) {
                        $roleName = "Modérateur";
                    }
                    $this->session->addMessage('success', 'Le rôle de ' . $user->getPseudo() . ' a bien été mis à jour, nouveau rôle : ' . $roleName);
                }            
            }
        }
        $this->http->dynamicRedirect('?route=adminUsers',$this->session);
    }

    public function updateUserStatus(Parameter $get) : void
    {
        if ($get->get('userId') && $get->get('statusId') && $get->get('token')) {

            $this->checkToken((string)$get->get('token'));

            $statusArray = [1,3];
            $user = $this->userDAO->getUser((int)$get->get('userId'));

            if ($user) {
                if (((int)$user->getRoleId() !== 1) && in_array((int)$get->get('statusId'), $statusArray)) {

                    $this->userDAO->updateUserStatus((int)$get->get('userId'),(int)$get->get('statusId'));

                    if ((int)$get->get('statusId') === 1) {
                        $statusName= "Relaxé";
                    } elseif ((int)$get->get('statusId') === 3) {
                        $statusName = "Banni";
                    } else{
                        $statusName = "???";
                    }
                    $this->session->addMessage('success', $user->getPseudo() . ' a bien été ' . $statusName);
                }            
            }
        }
        $this->http->dynamicRedirect('?route=adminUsers',$this->session);
    }

    public function deleteUser(Parameter $get) : void
    {
        if ($get->get('userId') && $get->get('token')) {

            $this->checkToken((string)$get->get('token'));

            $user = $this->userDAO->getUser((int)$get->get('userId'));

            if ($user) {

                if ((int)$user->getRoleId() !== 1) {

                    try {
                        $this->userDAO->deleteUser((int)$get->get('userId'));
                    } catch (Exception $error) {
                        throw new Exception('Erreur lors de la suppression de l\'utilisateur : ' . $error->getMessage());
                    }
                    
                    $this->session->addMessage('success', 'L\'utilisateur : ' . $user->getPseudo() . ' a bien été supprimé');
                } 
            }
        }
        $this->http->dynamicRedirect('?route=adminUsers',$this->session);
    }
}
