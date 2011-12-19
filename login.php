<?php

require_once 'Integration/NetTeamContainer.php';
require_once 'NetTeam.php';

define('NT3_APP_DIR', __DIR__ . '/../../app');
define('NT3_KERNEL_CLASS', 'AppKernel');
define('NT3_ENV', 'dev');
define('NT3_DEBUG', true);
define('NT3_ALIAS', '/login.php');

// $templating = NetTeamContainer::get('templating');
// $response = $templating->renderResponse('AcmeDemoBundle:Demo:index.html.twig');
// $response->send();

// $em = NetTeamContainer::get('doctrine.orm.entity_manager');
// var_dump($em);

// $router = NetTeamContainer::get('router');
// echo $router->generate('_welcome');

$app = NetTeam::init();
$app->run();
