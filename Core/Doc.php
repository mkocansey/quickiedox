<?php
namespace App\Core;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\MarkdownConverter;

class Doc
{
    private string $version;
    private string $page;

    public function __construct(string $version, string $page)
    {
        $this->version = $version;
        $this->page = $this->pathToMdFiles() . $this->appendMdExtension($page);
    }

    public function exists(string $page = null): bool
    {
        return file_exists(($page) ?? $this->page);
    }

    public function notFound(): string
    {
        return view('404');
    }

    public function load(string $page = null): \League\CommonMark\Output\RenderedContentInterface
    {
        $page = ($page !== null) ? $this->appendMdExtension($page) : $this->page;

        $config = [];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new AttributesExtension());
        $converter = new MarkdownConverter($environment);
        return $converter->convertToHtml(file_get_contents($page));
    }

    private function pathToMdFiles(): string
    {
        return  append_slash(
            realpath(
                './' . append_slash(App::get('docs_directory')).
                (($this->version !== '') ?
                    append_slash($this->version) : '')
            )
        );
    }

    public static function stripMdExtension(string $page): string
    {
        return (str_ends_with($page, '.md')) ? str_replace('.md', '', $page) : $page;
    }

    private function appendMdExtension(string $page): string
    {
        return (!str_ends_with($page, '.md')) ? $page.'.md' : $page;
    }

    public function navigation(): string
    {
        $nav_file = $this->pathToMdFiles() . App::get('nav_page');
        return ($this->exists($nav_file)) ? $this->load($nav_file) : App::get('message_if_no_navigation');
    }

}