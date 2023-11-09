<?php
namespace Deployer;
// require 'contrib/slack.php';

require 'recipe/common.php';

set('repository', 'git@github.com:mkocansey/quickiedox.git');
set('keep_releases', 3);
set('composer_options', 'update --no-scripts');

#slack specific settings
set('slack_webhook', 'https://webhook-url');
set('slack_success_text', 'Deployment from branch -> `{{branch}}` by *_{{user}}_* was successful');
set('slack_success_color', 'good');
set('slack_failure_text', 'Deployment to *{{target}}* by *_{{user}}_* failed :scream:');
set('slack_failure_color', 'danger');


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
Deployer::get()->tasks->remove('deploy');

desc('Confirm whether to deploy or not');
task('deploy', function(){
    `clear`;
    writeln("\n\n\n\n");
    if (! askConfirmation('Are you sure you want to deploy?')) {
        writeln("\n\n");
        warning('Deployment aborted by user');
        writeln("\n\n");
        die();
    }
    writeln("\n\n");
    writeln('===================================================================================================');
    invoke('deploy:common');
});

task('deploy:common', [
    'deploy:prepare',
    'deploy:publish',
    'deploy:config'
]);

desc('Run post deployment configurations');
task('deploy:config', [
    'deploy:run_composer',
]);

desc('run composer update');
task('deploy:run_composer', function(){
    cd(get('deploy_path').'/current');
    run('rm -f composer.lock && composer update --ignore-platform-reqs --no-scripts');
});

after('deploy:failed', 'deploy:unlock');
// check the http://deployer.org docs for how to send Slack notifications when deployment is done
// after('deploy', 'slack:notify:success');
// after('deploy:failed', 'slack:notify:failure');