<?php use \App\Core\App; ?>
<!doctype html>
<html>
    <head>
        <title><?php echo \App\Core\App::get('default_page_title') ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" href="/assets/images/quickiedox-icon.png" class="touch-icon" />
        <link rel="shortcut icon" href="/assets/images/quickiedox-icon.png" type="image/x-icon" class="fav-icon" />
        <link href="/assets/css/prism.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
        <link href="/assets/css/animate.min.css?<?php echo uniqid(); ?>" rel="stylesheet">
        <link href="/assets/css/quickiedox.css?<?php echo uniqid(); ?>" rel="stylesheet">
    </head>
    <body>
        <div class="bg-slate-100 shadow-sm shadow-blue-100/80 border-b border-slate-300/60 fixed w-full z-50 py-2.5">
            <div class="max-w-8xl mx-auto grid grid-cols-3">
                <div>
                    <a href="/<?php echo strip_slash(get_url_prefix(), -1) ?>"><img src="/assets/images/quickiedox-logo.svg" alt="QuickDox Logo" class="h-10 mt-0.5" /></a>
                </div>
                <div>
                    <div class="relative">
                        <input type="text" placeholder="Quick Search" class="absolute w-full border border-primary-200/70 rounded-full py-2 px-9 focus:outline-none focus:shadow-inner focus:ring-2 focus:ring-primary-500 text-primary-600" />
                        <span class="absolute mt-2.5 text-sm opacity-20 right-4 cursor-default">⌘K</span>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-5 h-5 absolute ml-3 mt-3 opacity-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-right flex items-center flex-row-reverse">
                    <a href="#" class="inline-block p-1 rounded-lg bg-primary-100 hover:bg-primary-400 hover:text-primary-100 text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                    </a>
                    <div class="relative inline-block mr-3 group">
                        <button class="bg-primary-100 text-primary-600 py-2 pl-3 pr-2 rounded-xl text-xs hover:bg-primary-400 hover:text-primary-100 tracking-wider" tabindex="2">
                            <?php echo variable('current_version', 'session') ?? App::get('default_doc_version') ?>
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="inline w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"></path>
                            </svg>
                        </button>
                        <div class="absolute -mt-8 rounded-lg bg-primary-100 text-primary-600 py-2 text-sm hidden group-hover:block">
                            <?php if(count(App::get('doc_versions')) > 1) {
                                foreach(App::get('doc_versions') as $branch) {
                            ?>
                                <a href="/<?php echo get_url_prefix()."{$branch}" ?>" class="block py-2 pl-3 pr-8 text-left border-b border-primary-200 hover:bg-primary-400 hover:text-primary-100"><?php echo $version; ?></a>
                            <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-8xl mx-auto px-4 flex">
            <div class="lg:!block z-40 inset-0 top-[82px] w-[17.5rem] pb-10 pt-6 overflow-y-auto border-r border-slate-100 fixed left-[max(0px,calc(50%-45rem))] right-auto px-4">
                <nav class="quickie-navigation">
                    <?php echo replace_version($navigation, $version); ?>
                </nav>
            </div>
            <div class="lg:pl-[17.5rem] z-30">
                <div class="max-w-3xl mx-auto pt-20 xl:max-w-none xl:ml-0 xl:mr-[15.5rem] xl:pr-16">
                    <div class="doc-content prose p-9 !z-30 <?php if(\App\Core\App::get('display_line_numbers')) echo ' line-numbers' ?>">
                        <?php echo replace_version($content, $version); ?>
                    </div>
                    <div class="side-nav-container fixed z-20 top-[3.8125rem] bottom-0 right-[max(0px,calc(50%-45rem))] w-[19.5rem] py-10 overflow-y-auto hidden xl:block">
                        <div class="side-nav"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script src="/assets/js/prism.js"></script>
<script src="/assets/js/quickiedox.js"></script>
<script>
    setPageTitle();
    collapseAll(true);
    highlightThisPageInNav('<?php echo strip_slash(get_url_prefix(),-1) ?>');
    activateNavActions();
    openUpNav();
</script>