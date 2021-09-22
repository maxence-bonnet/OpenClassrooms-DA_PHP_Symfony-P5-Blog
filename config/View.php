<?php

namespace App\config;

use App\config\Request;
use App\config\HTTP;

class View
{
    private $file;
    private $title;
    private $request;
    private $session;

    private $loader;
    private $twig;

    public function __construct()
    {
        $this->request = new Request();
        $this->session = $this->request->getSession();

        $this->loader = new \Twig\Loader\FilesystemLoader('../views');
        $this->twig = new \Twig\Environment($this->loader,[
            'cache' => false, // __DIR__ . '/tmp',
            'debug' => true
        ]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    }

    public function renderTwig($template, $data = [])
    {
        $data['session'] = $this->session;
        echo $this->twig->render($template, $data);     
    }

    public function render($template, $data = [])
    {
        $this->file = '../views/'. $template .'.html.php';
        $content  = $this->renderFile($this->file, $data);
        $view = $this->renderFile('../views/base.html.php', [
            'title' => $this->title,
            'content' => $content,
            'session' => $this->session
        ]);
        echo $view;
    }

    private function renderFile($file, $data)
    {
        if(file_exists($file)){
            extract($data);
            ob_start();
            require $file;
            return ob_get_clean();
        }
        HTTP::redirect('?route=notFound');
    }
}