<?php

use Symfony\Component\Dotenv\Dotenv;
use DG\BypassFinals;
use Symfony\Component\Process\Process;

require dirname(__DIR__).'/vendor/autoload.php';
DG\BypassFinals::enable();

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    passthru(sprintf('APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup', $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'], __DIR__));
}

$consolePath = sprintf('%s/bin/console', dirname(__DIR__));
$commandsToExecute = [
    [
        'doctrine:database:drop',
        '--force',
        '--if-exists',
        '--env=test',
    ],
    [
        'doctrine:database:create',
        '--env=test',
    ],
    [
        'doctrine:migrations:migrate',
        '--no-interaction',
        '--env=test',
    ]
];

foreach ($commandsToExecute as $commandArguments) {
    $processCommand = array_merge([$consolePath], $commandArguments);

    $process = new Process($processCommand);
    $process->run();

    if (!$process->isSuccessful()) {
        echo $process->getErrorOutput();
    } else {
        echo $process->getOutput();
    }
}

echo "\n";