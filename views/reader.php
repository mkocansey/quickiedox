<?php
    use \App\Core\App;
    $total_versioins = (null !== App::get('doc_versions')) ? count(App::get('doc_versions')) : 0;
    require_once 'header.php';
?>
    <body>
        <!--- TOP BAR --->
        <div class="bg-slate-100 dark:bg-slate-900 shadow-lg dark:shadow-none shadow-blue-100/80 border-b border-slate-300/60 dark:border-slate-800 fixed w-full z-50 py-2.5">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" onclick="show('.nav-column')"
                class="md:hidden absolute top-2.5 left-1 dark:text-slate-400 w-8 h-8 dark:bg-slate-800 bg-slate-200 px-1 rounded-md mobile-nav-launcher">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5"></path>
            </svg>
            <div class="max-w-8xl mx-auto flex px-4 md:p-0">
                <div class="basis-1/3">
                    <a href="<?php echo docs_home() ?>">
                        <img src="/assets/images/logo.svg" alt="QuickDox Logo" class="h-7 md:pb-1 ml-8 md:h-10 md:ml-0" />
                    </a>
                </div>
                <div class="relative hidden sm:block md:basis-1/3">
                    <input type="text" placeholder="Quick Search" onkeyup="search(this.value)" 
                        class="absolute w-full border border-primary-200/70 dark:border-slate-900 rounded-full py-2 px-9 focus:outline-none focus:shadow-inner focus:ring-2 focus:ring-primary-500 dark:ring-slate-800 text-primary-600 dark:text-primary-300 mt-[-1px] dark:bg-white/5 search placeholder:dark:opacity-50" />
                    <span class="absolute mt-2.5 text-sm opacity-20 dark:text-primary-200 right-4 cursor-default cmd-ctrl-k search-shortcut"></span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-5 h-5 absolute ml-3 mt-2.5 opacity-20 dark:text-primary-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                    </svg>
                </div>
                <div class="text-right flex items-center flex-row-reverse gap-3 basis-2/3 md:basis-1/3">
                    <a href="javascript:setTheme()" class="inline-block p-1.5 rounded-md bg-primary-300/30 dark:bg-slate-800 dark:text-primary-100 text-primary-600 hover:bg-primary-400 hover:text-primary-100 dark:opacity-80 dark:hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                    </a>
                    <a href="javascript:show('.search-bar'); javascript: domElement('.find').focus()" 
                        class="md:hidden inline-block p-1.5 rounded-md bg-primary-300/30 dark:bg-slate-800 dark:text-primary-100 text-primary-600 hover:bg-primary-400 hover:text-primary-100 dark:opacity-80 dark:hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                    </a>
                    <div class="relative <?php echo ($total_versioins > 1) ? 'inline-block' : 'hidden' ?> group">
                        <button class="bg-primary-300/30 text-primary-600 dark:bg-slate-800 dark:text-primary-100 py-2 pl-3 pr-2 rounded-lg text-xs hover:bg-primary-400 dark:opacity-80 dark:hover:opacity-100 hover:text-primary-100 tracking-wider" tabindex="2">
                            <?php echo variable('current_version', 'session') ?? App::get('default_doc_version') ?>
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="inline w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"></path>
                            </svg>
                        </button>
                        <div class="absolute -mt-8 rounded-lg bg-primary-100 dark:bg-slate-800 dark:text-primary-100 py-2 text-sm hidden group-hover:block">
                            <?php if($total_versioins > 1) {
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
        <!--- end top bar --->

        <!--- search box --->
        <div class="fixed md:mx-10 w-full bg-white/90 dark:bg-slate-900 md:top-[62px] z-50 shadow-2xl dark:shadow-sm shadow-blue-100/80 dark:shadow-gray-800 hidden search-bar opacity-90 md:opacity-100 md:border-none border-2 border-slate-700 top-4 rounded-lg md:rounded-none">
            <div class="flex md:hidden border-b border-slate-800 py-1">
                <div class="p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-slate-500 ml-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                    </svg>
                </div>
                <div class="flex-grow p-1 pl-0">
                    <input class="w-full p-1 ring-0 outline-none bg-transparent dark:placeholder:text-slate-500 dark:text-slate-400 mt-0.5 pr-2 find" placeholder="Search documentation" />
                </div>
                <div class="p-2 cursor-pointer" onclick="hide('.search-bar')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-slate-500 ml-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <div class="md:max-w-4xl mx-auto md:py-10 min-h-[200px]">
                this is a search bar
            </div>
        </div>
        <!--- end search box --->

        <div class="w-full lg:max-w-8xl mx-auto md:flex">
            <div class="nav-column hidden bg-white md:!block md:z-40 z-50 inset-0 top-0 md:top-[82px] md:w-[17.5rem] w-10/12 pb-10 md:pt-6 overflow-y-auto border-r border-slate-100 dark:border-slate-800/70 fixed md:left-[max(0px,calc(50%-45rem))] left-0 md:right-auto md:px-4 dark:bg-slate-800 md:bg-transparent md:dark:bg-transparent shadow-2xl md:shadow-none dark:shadow-slate-900">
                <div class="p-2 cursor-pointer text-right md:hidden mr-3" onclick="hide('.nav-column')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 dark:text-slate-500 inline-block">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <nav class="quickie-navigation">
                    <?php echo $navigation ?? ''; ?>
                </nav>
            </div>
            <div class="lg:pl-[17.5rem] z-30 pl-0">
                <div class="mx-auto pt-20 xl:max-w-none xl:ml-0 xl:mr-[15.5rem] xl:pr-16">
                    <div class="doc-content scroll-smooth prose p-4 pt-3 md:p-9 !z-30 <?php if(\App\Core\App::get('display_line_numbers')) echo ' line-numbers' ?>">
                        <?php echo $content ?? ''; ?>
                    </div>
                    <div class="side-nav-container fixed z-20 top-[3.8125rem] bottom-0 right-[max(0px,calc(50%-45rem))] w-[19.5rem] py-10 overflow-y-auto hidden xl:block">
                        <p class="py-4 pt-6 text-slate-400 text-xs uppercase tracking-wider">In This Document</p>
                        <div class="side-nav"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script src="/assets/js/prism.js"></script>
<script>
    setTheme(true);
    setPageTitle();
    collapseAll(true);
    highlightThisPageInNav('<?php echo strip_slash(get_url_prefix(),-1) ?>');
    activateNavActions();
    openUpNav();
    externalLinksOpenInNewWindow();
    writeCtrlOrCmd();
    listenForSearchShortcut();
    drawSidenav();
</script>