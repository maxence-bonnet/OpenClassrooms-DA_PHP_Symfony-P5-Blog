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
    private $loader;
    private $twig;

    public function __construct($session)
    {
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
        $this->twig->addGlobal('session',$session); 
    }

    public function renderTwig($template, $data = [])
    {
        echo $this->twig->render($template . '.twig', $data);     
    }
}