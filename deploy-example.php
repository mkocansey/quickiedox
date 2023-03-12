<?php
namespace Deployer;

require 'recipe/common.php';

set('repository', 'git@github.com:mkocansey/quickiedox.git');
set('keep_releases', 3);
set('composer_options', 'update --no-scripts');

// Hosts

host('staging')
    ->set('remote_user', 'deployment-user') // which user on your server will you deploy as
    ->set('hostname', 'test.quickiedox.com') // this can also be an IP address
    ->set('labels', [ 'stage'=> 'staging'])
    ->set('branch', 'development')
    ->set('deploy_path', '/var/www/html/test-quickiedox');

host('production')
    ->set('remote_user', 'deployment-user') // which user on your server will you deploy as
    ->set('hostname', 'quickiedox.com') // this can also be an IP address of the server where you want to deploy the docs
    ->set('labels', [ 'stage'=> 'production'])
    ->set('branch', 'main')
    ->set('deploy_path', '/var/www/html/quickiedox');

// Tasks
desc('Confirm whether to deploy or not');
//runLocally('confirm_deployment');
task('deploy', function(){
    writeln("\n\n\n");
    if (! askConfirmation('Are you sure you want to deploy?')) {
        warning('Deployment aborted by user');
        exit;
    } 
    writeln('===================================================================================================');
    invoke('build');
});


desc('Deploy the project');
task('build', [
    'deploy:prepare',
    'deploy:publish',
    'deploy:run_composer',
    'deploy:success'
]);

desc('run composer update');
task('deploy:run_composer', function(){
    cd(get('deploy_path').'/current');
    run('rm -f composer.lock && composer update --ignore-platform-reqs --no-scripts');
});

after('deploy:failed', 'deploy:unlock');

// check the http://deployer.org docs for how to send Slack notifications when deployment is done
// after('deploy:failed', 'slack:notify:failure');
//after('deploy', 'slack:notify:success');