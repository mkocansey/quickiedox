<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Doc;
use App\Core\Session;

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
        Session::put([ 'current_version' => $this->version ]);
    }

    public function index(): string|bool|null
    {
        if(App::get('show_index_page')) return view('home');
        return $this->doc->load();
    }

    public function notFound(): string|null
    {
        return view('404'); //$this->doc->notFound();
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

    public function clone_init( bool $has_error = false, string $action = 'pin'): mixed
    {
        if(variable('pin', 'post')) {
            $pin = variable('pin', 'post');
            $has_error = ($pin !== App::get('git_clone_pin'));
            if(! $has_error) {
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

    public function clone()
    {
        if(! variable('clone', 'session')) {
            die(api_response([
                'status' => false, 
                'message' => 'you cannot clone without first verifying your pin'
            ]));
        }
        $repo_directory = dirname(__DIR__) . '/' . App::get('docs_directory');
        if(!is_dir($repo_directory)) mkdir($repo_directory);
        if(!is_writable($repo_directory)) {
            die(api_response([
                'status' => false,
                'message' => "Unable to write into $repo_directory"
            ]));
        }

        $doc_versions =  App::get('doc_versions');
        $repo_url = App::get('md_repo_url');
        ini_set('max_execution_time', '300');

        if (count($doc_versions) < 1) {
            die(api_response([
                'status' => false,
                'message' => '$doc_versions variable must contain at least one branch to clone'
            ]));
        }

        $this_version = (int) variable('clone', 'session');
        $version = $doc_versions[$this_version] ?? null;
        $version_directory = append_slash($repo_directory).$version;

        /* if( !($version) ) {
            die(api_response([
                'status' => true,
                'message' => '<br />no more branches to clone '
            ]));
        } */

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
        if( $this_version < count($doc_versions) ) {
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
                            get_url_prefix().Doc::stripMdExtension(App::get('default_doc_page')))
            ]));
        }
    }

}