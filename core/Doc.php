<?php
namespace QuickieDox;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContentInterface;

class Doc
{
    private string $version;
    private string $page;

    /**
     * @param string $version
     * @param string $page
     */
    public function __construct(string $version, string $page)
    {
        $this->version = $version;
        $this->page = $this->pathToMdFiles() . $this->appendMdExtension($page);
    }

    /**
     * Check if requested markdown file exists
     * @param string|null $page
     * @return bool
     */
    public function exists(string $page = null): bool
    {
        return file_exists(($page) ?? $this->page);
    }

    /**
     * 404 view to return when markdown files don't exist
     * @return string
     */
    public function notFound(): string
    {
        return view('404');
    }

    /**
     * Load and convert markdown file to HTML
     * @param string|null $page
     * @param bool $isPage
     * @return RenderedContentInterface
     * @throws \Exception
     */
    public function load(string $page = null, bool $isPage = true): \League\CommonMark\Output\RenderedContentInterface
    {

        // if $isPage=false, we want to convert a markdown string not a whole file
        $page = ($isPage) ? (($page !== null) ? $this->appendMdExtension($page) : $this->page) : $page;

        $config = [
            'html_input' => (! App::get('allow_html_in_markdown')) ? 'strip' : 'allow',
            'allow_unsafe_links' => false
        ];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new AttributesExtension());
        $converter = new MarkdownConverter($environment);
        return $converter->convertToHtml(($isPage)?file_get_contents($page):$page);
    }

    /**
     * Get absolute path to markdown files directory
     * @return string
     * @throws \Exception
     */
    private function pathToMdFiles(): string
    {
        return append_slash(
            realpath(
                './' . append_slash(App::get('docs_directory')).
                (($this->version !== '') ? append_slash($this->version) : '')
            )
        );
    }

    /**
     * Remove .md extension from file
     * @param string $page
     * @return string
     */
    public static function stripMdExtension(string $page): string
    {
        return (str_ends_with($page, '.md')) ? str_replace('.md', '', $page) : $page;
    }

    /**
     * Append .md extension to file
     * @param string $page
     * @return string
     */
    private function appendMdExtension(string $page): string
    {
        return (!str_ends_with($page, '.md')) ? $page.'.md' : $page;
    }

    /**
     * Load navigation file for current version
     * @return string
     * @throws \Exception
     */
    public function navigation(): string
    {
        $nav_file = $this->pathToMdFiles() . App::get('nav_page');
        return ($this->exists($nav_file)) ? $this->load($nav_file) : App::get('message_if_no_navigation');
    }

}