<?php

namespace App\Src\Controller\BackController;

use App\Config\Parameter;
use App\Src\Controller\Controller;

class BackUserController extends BackController
{
    public function updateUserRole(int $userId, int $roleId) : void
    {
        $this->checkAdmin();
        
        $rolesArray = [1,2,3,4];
        $user = $this->userDAO->getUser($userId);
        if ($user) {
            if (((int)$user->getRoleId() !== 1) && ((int)$user->getRoleId() !== $roleId) && in_array($roleId, $rolesArray)) {

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
        $this->http->dynamicRedirect('?route=adminUsers',$this->session);
    }

    public function updateUserStatus(int $userId, int $statusId) : void
    {
        $this->checkAdmin();
        $statusArray = [1,3];
        $user = $this->userDAO->getUser($userId);

        if ($user) {
            if (((int)$user->getRoleId() !== 1) && in_array($statusId, $statusArray)) {

                $this->userDAO->updateUserStatus($userId,$statusId);

                if ($statusId === 1) {
                    $statusName= "Relaxé";
                } elseif ($statusId === 3) {
                    $statusName = "Banni";
                } else{
                    $statusName = "???";
                }
                $this->session->addMessage('success', $user->getPseudo() . ' a bien été ' . $statusName);
            }            
        }
        $this->http->dynamicRedirect('?route=adminUsers',$this->session);
    }
}