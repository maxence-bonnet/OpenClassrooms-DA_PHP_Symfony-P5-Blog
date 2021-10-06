<?php

namespace App\Src\Controller\BackController;

use App\Config\Parameter;
use App\Src\Controller\Controller;
use App\Src\Utils\URL;

class BackAdminController extends BackController
{
    protected $limitArray = [10,20,30,40,50,75,100];
    private $defaultLimit = 20;

    public function administration()
    {
        $this->checkAdmin();
        $this->setPreviousURI();

        $this->data['comments'] = $this->commentDAO->getComments([
            'limit' => 10,
            'validated' => "pending",
            'orderBy' => ['column' => 'c.created_at',
                            'order' => 'DESC']
        ]);
        $this->data['articles'] = $this->articleDAO->getArticles([
            'limit' => 10,
            'orderBy' => ['column' => 'a.created_at',
                            'order' => 'DESC']
        ]);
        $this->data['users'] = $this->userDAO->getUsers([
            'limit' => 10,
            'orderBy' => ['column' => 'u.created_at',
                            'order' => 'DESC']
        ]);

        $this->data['title'] = 'Administration';
        return $this->view->renderTwig('administration', $this->data);
    }

    public function adminComments(Parameter $get)
    {
        $this->checkAdmin();
        $this->setPreviousURI();

        $this->parameters = $this->getCleanParameters($get->all(),'comment');
    
        $this->data['page'] = isset($this->parameters['page']) && $this->parameters['page'] > 1 ? $this->parameters['page'] : 1;
        unset($this->parameters['page']);
        $this->data['title'] = 'Administration des commentaires';
        $this->data['get'] = $get;

        $this->parameters['limit'] = $this->defaultLimit ;
        if (in_array((int)$get->get('limit'),$this->limitArray)) {
            $this->parameters['limit'] = $get->get('limit');
        }

        if (isset($this->parameters['validated']) && $this->parameters['validated'] === 'all') {
            unset($this->parameters['validated']);
        }

        $this->data['totalCommentsCount'] = (int) $this->commentDAO->countComments($this->parameters);

        $this->data['pages'] = ceil($this->data['totalCommentsCount']/$this->parameters['limit']);

        if ($this->data['page'] <= $this->data['pages'] && $this->data['page'] > 1) {
            $this->parameters['offset'] = $this->parameters['limit']*($this->data['page'] - 1);     
        } else {
            $this->data['page'] = 1;
            $this->parameters['offset'] = 0;
        }

        $this->data['comments'] = $this->commentDAO->getComments($this->parameters);

        $this->data['pageCommentsCount'] = count($this->data['comments']);

        $this->data['previousPageURL'] = $this->data['page'] > 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] - 1]) . "#resultsTable" : null;
        $this->data['nextPageURL'] = $this->data['page'] < $this->data['pages'] && $this->data['pages'] !== 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] + 1]) . "#resultsTable" : null;
       
        $this->data['articles'] = $this->buildArticlesList();

        $this->data['users'] = $this->buildUsersList();

        $this->data['limits'] = $this->buildLimitsList();

        return $this->view->renderTwig('adminComments', $this->data);             
    }

    public function adminArticles(Parameter $get)
    {
        $this->checkAdmin();
        $this->setPreviousURI();

        $this->parameters = $this->getCleanParameters($get->all(),'article');
    
        $this->data['page'] = isset($this->parameters['page']) && $this->parameters['page'] > 1 ? $this->parameters['page'] : 1;
        unset($this->parameters['page']);
        $this->data['title'] = 'Administration des articles';
        $this->data['get'] = $get;

        $this->parameters['limit'] = $this->defaultLimit ;
        if (in_array((int)$get->get('limit'),$this->limitArray)) {
            $this->parameters['limit'] = $get->get('limit');
        }
        
        if (isset($this->parameters['all'])) {
            unset($this->parameters['published']);
            unset($this->parameters['private']);
            unset($this->parameters['standby']);
            unset($this->parameters['all']);
        }

        $this->data['totalArticlesCount'] = (int) $this->articleDAO->countArticles($this->parameters);

        $this->data['pages'] = ceil($this->data['totalArticlesCount']/$this->parameters['limit']);

        if ($this->data['page'] <= $this->data['pages'] && $this->data['page'] > 1) {
            $this->parameters['offset'] = $this->parameters['limit']*($this->data['page'] - 1);     
        } else {
            $this->data['page'] = 1;
            $this->parameters['offset'] = 0;
        }

        $this->data['articles'] = $this->articleDAO->getArticles($this->parameters);

        $this->data['pageArticlesCount'] = count($this->data['articles']);

        $this->data['previousPageURL'] = $this->data['page'] > 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] - 1]) . "#resultsTable" : null;
        $this->data['nextPageURL'] = $this->data['page'] < $this->data['pages'] && $this->data['pages'] !== 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] + 1]) . "#resultsTable" : null;

        $this->data['users'] = $this->buildUsersList();

        $this->data['categories'] = $this->buildCategoriesList();

        $this->data['limits'] = $this->buildLimitsList();

        return $this->view->renderTwig('adminArticles', $this->data);
    }

    public function adminUsers(Parameter $get)
    {
        $this->checkAdmin();
        $this->setPreviousURI();

        $this->parameters = $this->getCleanParameters($get->all(),'user');
    
        $this->data['page'] = isset($this->parameters['page']) && $this->parameters['page'] > 1 ? $this->parameters['page'] : 1;
        unset($this->parameters['page']);
        $this->data['title'] = 'Administration des utilisateurs';
        $this->data['get'] = $get;

        $this->parameters['limit'] = $this->defaultLimit ;
        if (in_array((int)$get->get('limit'),$this->limitArray)) {
            $this->parameters['limit'] = $get->get('limit');
        }

        if (isset($this->parameters['allUserStatus'])) {
            unset($this->parameters['online']);
            unset($this->parameters['offline']);
            unset($this->parameters['banned']);
            unset($this->parameters['allUserStatus']);
        }

        if (isset($this->parameters['allUserRoles'])) {
            unset($this->parameters['admin']);
            unset($this->parameters['moderator']);
            unset($this->parameters['editor']);
            unset($this->parameters['user']);
            unset($this->parameters['allUserRoles']);
        }

        $this->data['totalUsersCount'] = (int) $this->userDAO->countUsers($this->parameters);

        $this->data['pages'] = ceil($this->data['totalUsersCount']/$this->parameters['limit']);

        if ($this->data['page'] <= $this->data['pages'] && $this->data['page'] > 1) {
            $this->parameters['offset'] = $this->parameters['limit']*($this->data['page'] - 1);     
        } else {
            $this->data['page'] = 1;
            $this->parameters['offset'] = 0;
        }

        $this->data['users'] = $this->userDAO->getUsers($this->parameters);

        $this->data['pageUsersCount'] = count($this->data['users']);

        $this->data['previousPageURL'] = $this->data['page'] > 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] - 1]) . "#resultsTable" : null;
        $this->data['nextPageURL'] = $this->data['page'] < $this->data['pages'] && $this->data['pages'] !== 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] + 1]) . "#resultsTable" : null;

        $this->data['limits'] = $this->buildLimitsList();
        
        return $this->view->renderTwig('adminUsers', $this->data);
    }
}