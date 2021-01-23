<?php /** @noinspection ALL */

namespace Deployer;

require 'recipe/symfony.php';

set('repository', 'https://github.com/ivanstan/app2mail');
set('git_tty', true);
set('bin_dir', 'bin');
set('http_user', 'glutenfr');
set('writable_mode', 'chmod');
set('default_stage', 'production');
add('shared_files', ['.env']);
add('shared_dirs', ['var', 'config/secrets']);
add('writable_dirs', []);

host('ivanstanojevic.me')
    ->user('glutenfr')
    ->port(2233)
    ->stage('production')
    ->set('deploy_path', '~/projects/app2mail.ivanstanojevic.me');

task('test', function () {
    set('symfony_env', 'dev');
//    runLocally('bin/phpunit');
});

task('dump-autoload', function () {
    run('composer dump-env prod');
});

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:assets',
    'deploy:vendors',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'dump-autoload',
    'deploy:writable',
    'database:migrate',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

before('deploy', 'test');
after('deploy:failed', 'deploy:unlock');
