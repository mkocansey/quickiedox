<?php
namespace QuickieDox\Controllers;

use QuickieDox\App;
use QuickieDox\Doc;
use QuickieDox\Session;

class DocController
{
    private Doc $doc;
    private string $page;
    private string $version;
    private string $error_page;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->page = variable('dynamic_route_params')['page'] ?? App::get('default_doc_page');
        $this->version = (isset(variable('dynamic_route_params')['version']) && in_array(variable('dynamic_route_params')['version'], App::get('doc_versions'))) ?
            variable('dynamic_route_params')['version'] :
            App::get('default_doc_version');

        $this->error_page = 'views/404.md';
        $this->doc = new Doc($this->version, $this->page);
        Session::put([ 'current_version' => $this->version ]);
    }

    /**
     * Return either home page or default documentation page
     * @return string
     * @throws \Exception
     */
    public function index(): string
    {
        if (App::get('show_index_page')) return view('home');
        return $this->read();
    }

    /**
     * Display 404 page
     * @return string
     */
    public function notFound(): string
    {
        return view('404');
    }

    /**
     * Load and read a document
     * @return string
     * @throws \Exception
     */
    public function read(): string
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

    /**
     * Format 404 page
     * @param string $content
     * @return string
     */
    private function format404(string $content): string
    {
        return '<div class="notfound">' . $content . '</div>';
    }

    /**
     * Initial validations before cloning
     * @param bool $has_error
     * @param string $action
     * @return string
     * @throws \Exception
     */
    public function clone_init( bool $has_error = false, string $action = 'pin'): string
    {
        if (variable('pin', 'post')) {
            $pin = variable('pin', 'post');
            $has_error = ($pin !== App::get('git_clone_pin'));
            if (! $has_error) {
                $action = 'clone';
                Session::put([ 'clone' => 'start' ]);
            }
        }

        return view('pin', [
            'can_clone' => (strlen(App::get('git_clone_pin')) >= 6 && App::get('md_repo_url') !== ''),
            'action' => $action,
            'has_error' => $has_error
        ]);
    }

    /**
     * Clone or update documentation versions from Git
     * @return void
     * @throws \Exception
     */
    public function clone()
    {
        if (! variable('clone', 'session')) {
            die(api_response([
                'status' => false, 
                'message' => 'you cannot clone without first verifying your pin'
            ]));
        }
        $repo_directory = dirname(__DIR__) . '/' . App::get('docs_directory');
        if (!is_dir($repo_directory)) {
            if ( ! mkdir($repo_directory) ) {
                die(api_response([
                    'status' => false,
                    'message' => "<br />Unable to create $repo_directory. Kindly create the directory yourself and try cloning again."
                ]));
            }
        }
        if (!is_writable($repo_directory)) {
            die(api_response([
                'status' => false,
                'message' => "<br />Unable to write into $repo_directory"
            ]));
        }

        $doc_versions =  App::get('doc_versions');
        $repo_url = App::get('md_repo_url');

        // if your cloning takes longer than usual because you have several files in your repo,
        // you can uncomment the line below to increase PHP's execution time
//        ini_set('max_execution_time', '300');

        if (count($doc_versions) < 1) {
            die(api_response([
                'status' => false,
                'message' => '$doc_versions variable must contain at least one branch to clone'
            ]));
        }

        $this_version = (int) variable('clone', 'session');
        $version = $doc_versions[$this_version] ?? null;
        $version_directory = append_slash($repo_directory).$version;

        if (is_dir($version_directory)) {
            chdir($version_directory);
            exec("git reset --hard origin/$version && git pull && git clean -xdf");
            $message =  sprintf("<br>Updated version <b>%s</b>", $version);
        } else {
            if (! mkdir($version_directory) ) {
                die(api_response([
                    'status' => false,
                    'message' => "unable to create directory $version"
                ]));
            }
            $message =  sprintf(
                "<br>cloned %s of %s versions >> version <b>%s</b>",
                ($this_version+1),
                count($doc_versions),
                $version
            );
            exec("git clone --branch $version --single-branch $repo_url $version_directory 2>&1");
        }
        if ($this_version < count($doc_versions) ) {
            $this_version++;
            Session::put(['clone' => $this_version]);
            die(api_response([
                'status' => true,
                'data' => [ 'branch' => $this_version ],
                'message' => $message
            ]));
        } else {
            Session::forget(['clone']);
            die(api_response([
                'status' => true,
                'message' => sprintf('<br />DONE...<a href="%s">Read Documentation</a>',
                get_url_prefix().
                    append_slash(App::get('default_doc_version')).
                    Doc::stripMdExtension(App::get('default_doc_page'))
                )
            ]));
        }
    }

    /**
     * @throws \Exception
     */
    public function search()
    {
        $keyword = variable('keyword', 'get');
        $repo_directory = dirname(__DIR__) . '/' . App::get('docs_directory');
        $version_directory = append_slash($repo_directory).$this->version;
        $files = scandir($version_directory);
        $results = [];
        $headings = [];
        $allowed_tags = null;
        if (count($files) > 0 ) {
            foreach ($files as $file) {
                // look through all .md files except the navigation file
                if (str_ends_with($file, '.md') && !str_contains($file, App::get('nav_page'))) {
                    $pattern = "/^.*$keyword.*\$/im";
                    $fh = fopen(append_slash($version_directory).$file, 'r') or die($php_errormsg);
                    while (!feof($fh)) {
                        $line = fgets($fh, 4096);
                        if(preg_match('/^(#)+/', $line)) $headings[] = $line;
                        if (preg_match($pattern, $line)) {
                            $results[] = (object) [
                                'file' => Doc::stripMdExtension($file),
                                'heading' => trim(preg_replace("/^#+/", '', end($headings))),
                                'text' => highlight_search(strip_tags($this->doc->load($line, false), $allowed_tags), $keyword)
                            ];
                        }
                    }
                    fclose($fh);
                }
            }
            die(api_response([
                'status' => true,
                'data' => [
                    'total' => count($results),
                    'results' => $results
                ]
            ]));
        }
        die(api_response([
            'status' => false,
            'message' => "Nothing found for $keyword"
        ]));
    }
}