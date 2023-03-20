<?php
return [

    /**
     * what environment are you running the docs in.
     * development, staging or production.
     * this variable is unused in QuickieDox itself but may be relevant to you.
     */
    'environment'           => getenv("ENVIRONMENT") ?: 'development',

    /**
     * Default page title displayed in the browser title bar.
     * Specific documentation pages are appended to this page title.
     * The installation page for example will have the title Api Documentation: Installation
     */
    'default_page_title'    => 'QuickieDox Documentation',

    /** 
     * if true, a button is displayed on the docs pages that allows users 
     * to test the endpoints on that page
     */
    'allow_testing'         => getenv("ALLOW_TESTING") ?: false,
    
    /**
     * if allow_testing = true, what url will be called when users are 
     * making test calls to your endpoints
     */ 
    'testing_url'           => getenv("TESTING_URL") ?: '',
    
    /**
     * some API docs (like the Laravel docs) have a landing page that has other info 
     * with a button/link the user has to click on before diving into the docs
     *  */
    'show_index_page'       => getenv('SHOW_INDEX_PAGE') ?: true,
    
    /**
     * if your doc pages require users to be logged in. If this is set to true, you will 
     * need to update core/Auth.php to write in your authentication logic.
     * authenticating users is not handled in this code base. 
     *  */
    'require_signin'       => getenv('REQUIRE_SIGNIN') ?: false,
    
    /**
     * if require_signin = true, all pages will check for the existence of the session 
     * variable defined in core/Authentication.php. you can however, whitelist pages you
     * want to make accessible all the time
     *  */
    'whitelisted_docs'  => [],

    /**
     * directory where docs will be loaded from. the default is a docs directory at the root 
     * of your codebase. whatever path you define must exist
     */
    'docs_directory'    => getenv('DOCS_DIRECTORY') ?: 'markdown',

    /**
     * what are the various documentation versions you want to load and make available to users. 
     * it is expected that versions correspond to branches in your documentation repo. for example, 
     * if you want to display versions 1.0, 2.0 and 3.x, you will actually need to have the branches 
     * 1.0, 2.0 and 3.x in your git repository. corresponding directories will be created to match each
     * doc version. corresponding docs files will then be git cloned into their respective directories.
     * example: markdown/2.0/installation.md.
     * Even if your documentation is not versioned, you will need to provide the branch to clone the
     * default documentation from. In the case of QuickieDox, our docs are not versioned, but the documentation
     * files are in the 'main' branch so that is what will be listed in the variable below
     */
    'doc_versions'      => [
        'main'
    ],

    /**
     * what is the default documentation page. The very first page users will be taken to when
     * they access your documentation url
     */
    'default_doc_page'  => getenv('DEFAULT_DOC_PAGE') ?: 'overview.md',

    /**
     * the name of the file that contains your navigation
     */
    'nav_page'  => getenv('NAV_PAGE') ?: 'navigation.md',

    /**
     * what word should precede the url to your docs. The default is docs.
     * this means your docs url will become https://yourdomain.com/docs
     * if you are running the documentation from a url like docs.myapi.com, you
     * really wouldn't want the prefix to be docs/, otherwise the resulting url
     * will look like https://docs.myqpi.com/docs. this value is used in routes.php
     * to map urls to controllers
     */
    'docs_url_prefix'   => getenv('DOCS_URL_PREFIX') ?: 'docs',

    /**
     * message to display of no navigation file is found.
     * navigation page here is whatever has been defined for nav_page above
     */
    'message_if_no_navigation' => 'no navigation.md file found',

    /**
     * by default line numbers in code snippets are turned off.
     * you can turn them on globally here, otherwise, you can still
     * display line numbers on specific code blocks using inline attributes.
     * see https://quickiedox.com/docs/main/markdown-attributes
     */
    'display_line_numbers' => getenv('DISPLAY_LINE_NUMBERS') ?: false,

    /**
     * by default HTML tags in .md files are stripped, allowing only valid markdown syntax
     */
    'allow_html_in_markdown' => getenv('ALLOW_HTML_IN_MARKDOWN') ?: false,

    /**
     * When cloning your  .md doc files in the browser, you will need to
     * verify using the PIN specified in this variable.
     * Because QuickieDox is open source, anyone can know there is a url
     * for pulling in markdown files but cannot proceed without your PIN
     */
    'git_clone_pin' => getenv('GIT_CLONE_PIN'),

    /**
     * URL to the repo where the markdown files that make up your
     * docs pages are located
     */
    'md_repo_url' => getenv('MD_REPO_URL'),

];