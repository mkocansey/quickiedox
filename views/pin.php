<!doctype html>
<html>
    <head>
        <title>QuickieDox: Enter PIN to clone repo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" href="/assets/images/quickiedox-icon.png" class="touch-icon" />
        <link rel="shortcut icon" href="/assets/images/quickiedox-icon.png" type="image/x-icon" class="fav-icon" />
        <link href="/assets/css/quickiedox.css" rel="stylesheet">
        <script src="/assets/js/quickiedox.js"></script>
    </head>
    <body>
        <div class="h-screen flex">
            <div class="container mx-auto max-w-sm w-full lg:w-1/2 md:w-3/4 my-auto doc-content">
                <img src="/assets/images/quickiedox-logo.svg" alt="QuickieDox Logo" class="h-12 mb-16 mx-auto" />
                <?php if( $can_clone) { ?>
                    <?php if($action == 'pin') { ?>
                        <form method="post" action="/get-markdown">
                            <div class="opacity-40 py-4">Clone markdown files from: <br /><?php echo App\Core\App::get('md_repo_url'); ?></div>
                            <?php if(variable('pin') && $has_error) { ?>
                                <div class="bg-rose-100 text-rose-700 px-4 py-2 mb-4">
                                    You entered an invalid PIN
                                </div>
                            <?php } ?>
                            <input type="text" name="pin" placeholder="Enter PIN" class="border border-slate-300 outline-none text-xl p-4 focus:border-slate-600 w-full tracking-[1.5em] peer placeholder:tracking-normal placeholder:text-left text-center" />
                            <button type="submit" class="p-4 bg-primary-500 tracking-wide hover:bg-primary-600 text-white rounded-xl w-full mt-4 peer-placeholder-shown:invisible">Verify & Clone</button>
                        </form>
                    <?php } elseif($action == 'clone') { ?>
                        <div class="text-xs leading-6 clone-info">cloning docs please wait...</div>
                        <script>ajaxCall('/clone?branch=0', 'cloneCallback');</script>
                <?php }?>
                <?php } else { ?>
                    <div class="opacity-40 py-4 text-center">You first need to provide a value for <code class="inline font-bold">MD_REPO_URL</code> and <code class="inline font-bold">GIT_CLONE_PIN</code> in your .env file or <code class="inline font-bold">md_repo_url</code> and <code class="inline font-bold">git_clone_pin</code> in config.php</div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>