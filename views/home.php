<?php
    use \App\Core\Doc;
    use \App\Core\App;
    $page_title = 'QuickieDox: Quickly create elegant documentation from markdown files';
    require_once 'header.php';
?>
    <body>
        <div class="h-screen flex bg-slate-100">

            <div class="container mx-auto w-full lg:w-1/2 md:w-3/4 my-auto">
                <img src="/assets/images/logo.svg" alt="QuickieDox Logo" class="h-16 mx-auto" />
                <img src="/assets/images/create-docs.svg" alt="Create Docs" class="mx-auto w-3/5 my-16 animate__animated animate__fadeIn" />

                <h1 class="text-5xl md:text-6xl font-semibold text-center text-slate-700 animate__animated animate__fadeIn">
                    Quickly create elegant documentation from markdown files
                </h1>
                <div class="text-center pt-12 pb-4">
                    <a href="<?php echo get_url_prefix().append_slash(App::get('default_doc_version')).Doc::stripMdExtension(App::get('default_doc_page')) ?>"
                       class="py-4 px-10 inline-block text-xl bg-primary-500 tracking-wide hover:bg-primary-600 text-white rounded-xl shadow-sm shadow-slate-400">
                      Read Documentation
                    </a>
                </div>
            </div>
        </div>
        <span class="hidden border border-red-400 text-center"></span>
    </body>
</html>