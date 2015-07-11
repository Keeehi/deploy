<?php
require __DIR__ . '/../vendor/autoload.php';

$appDirectory = __DIR__;
$logDirectory = $appDirectory . '/../log';
$tempDirectory = $appDirectory .'/../temp';

$configurator = new Nette\Configurator;

//$configurator->setDebugMode(true); //$forced=true;  //pro násilné zapnutí debuggingu na produkčním servru.
//$configurator->setDebugMode(false); //$forced=true; //pro násilné vypnutí debuggingu na vývojovém počítači.

if (!file_exists($logDirectory) && !is_dir($logDirectory)) {
    mkdir($logDirectory);
}
$configurator->enableDebugger($logDirectory);


if (!file_exists($tempDirectory) && !is_dir($tempDirectory)) {
    mkdir($tempDirectory);
}
$configurator->setTempDirectory($tempDirectory);


$configurator->addConfig($appDirectory . '/config/config.neon');

if (file_exists($appDirectory . '/config/config.local.neon')) {
    $configurator->addConfig($appDirectory . '/config/config.local.neon');
}


//if (PHP_SAPI === 'cli' || ($configurator->isDebugMode() && (!isset($forced) || $forced !== true))) {
if (PHP_SAPI === 'cli' || $configurator->isDebugMode()) {
    \Tracy\Debugger::$maxDepth = 6;
}



$configurator->createRobotLoader()
    ->addDirectory($appDirectory)
    ->register();

$container = $configurator->createContainer();

return $container;