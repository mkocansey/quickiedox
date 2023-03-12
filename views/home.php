<?php
    use App\Core\App;
    use App\Core\Doc;
    require_once 'views/header.php';
?>
    <div class="h-screen flex bg-slate-500">

        <div class="container mx-auto w-full lg:w-1/2 md:w-3/4 my-auto">
            <img src="/assets/images/quickiedox-white-logo.svg" alt="QuickieDox Logo" class="h-16 mx-auto" />
            <img src="/assets/images/create-docs.svg" alt="Create Docs" class="mx-auto w-3/4 mt-12" />

            <h1 class="text-5xl md:text-6xl font-semibold mt-16 text-center text-white/50 drop-shadow-md">
                Quickly create elegant documentation from markdown files
            </h1>
            <div class="text-center pt-12 pb-4">
                <a href="<?php echo get_url_prefix().Doc::stripMdExtension(App::get('default_doc_page')) ?>"
                   class="hover:bg-black text-white py-4 px-10 rounded-full inline-block text-xl tracking-wider cursor-pointer bg-slate-800">
                  Read Docs
                </a>
            </div>
        </div>
    </div>
    <span class="hidden border border-red-400 text-center"></span>
<?php require_once 'views/footer.php'; ?>