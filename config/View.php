<?php

namespace App\config;

use App\config\Request;
use App\config\HTTP;

use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

class View
{
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
        $this->twig->addExtension(new MarkdownExtension());
        $this->twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
            public function load($class) {
                if (MarkdownRuntime::class === $class) {
                    return new MarkdownRuntime(new DefaultMarkdown());
                }
            }
        });      
    }

    public function renderTwig($template, $data = [])
    {
        $data['session'] = $this->session;
        echo $this->twig->render($template . '.twig', $data);     
    }
}