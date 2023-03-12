<?php

namespace App\Controllers;

use App\Core\App,
    App\Core\Doc;

class DocController
{
    private Doc $doc;
    private mixed $page;
    private string $version;
    private string $error_page;

    public function __construct()
    {
        $this->page = variable('dynamic_route_params')['page'] ?? App::get('default_doc_page');
        $this->version = variable('dynamic_route_params')['version'] ?? App::get('default_doc_version');
        $this->error_page = 'views/404.md';
        $this->doc = new Doc($this->version, $this->page);
    }

    public function index(): string|bool|null
    {
        if( App::get('show_index_page') )  {
            return view('home');
        }

        return $this->doc->load();
    }

    public function notFound(): string|null
    {
        return $this->doc->notFound();
    }

    public function read(): string|null
    {
        $html = ($this->doc->exists()) ?
                $this->doc->load() :
                $this->format404($this->doc->load($this->error_page));

        return view('reader',
            [
                'content' => $html,
                'navigation' => $this->doc->navigation(),
                'version' => $this->version
            ]);

    }

    private function format404(string $content): string
    {
        return '<div class="notfound">' . $content . '</div>';
    }


}