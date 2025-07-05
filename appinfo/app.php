<?php
declare(strict_types=1);

namespace OCA\NewsBot\AppInfo;

use OCP\AppFramework\App;
use OCA\NewsBot\Service\NewsService;

$app = new App('newsbot');
$container = $app->getContainer();

$container->registerService(NewsService::class, function ($c) {
    return new NewsService(
        $c->query('OCP\\IConfig')
    );
});
