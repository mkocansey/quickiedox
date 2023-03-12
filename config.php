<?php
return [

    'environment'           => getenv("ENVIRONMENT") ?: 'development',
    
    'default_page_title'    => 'API Documentation',

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
     * need to update Core/Auth.php to write in your authentication logic.
     * authenticating users is not handled in this code base. 
     *  */
    'require_signin'       => getenv('REQUIRE_SIGNIN') ?: false,
    
    /**
     * if require_signin = true, all pages will check for the existence of the session 
     * variable defined in Core/Authentication.php. you can however, whitelist pages you 
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
     * example: docs/2.0/installation.md.
     * if no doc versions are entered in the array, it is assumed you are loading all docs files from your 
     * root docs_directory (with no sub folders). example: docs/installation.md
     */
    'doc_versions'      => [],

    /**
     * what is the default documentation page
     */
    'default_doc_page'  => getenv('DEFAULT_DOC_PAGE') ?: 'overview.md',

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

    'message_if_no_navigation' => 'no navigation.md file found',

    'display_line_numbers' => getenv('DISPLAY_LINE_NUMBERS') ?: false,

];