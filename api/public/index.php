<?php

use App\Resource;

require_once '../vendor/autoload.php';

$app = new Slim\App();

$app->get('/{resource}/{id}', function ($request, $response) {
    $resource = $request->getAttribute('resource');
    $id = $request->getAttribute('id');

    $resource = Resource::load($resource);

    if ($resource === null) {
        Resource::response(Resource::STATUS_NOT_FOUND);
    } else {
        $resource->get($id);
    }

    #return $response->withJson(['1'=>'2']);
});

$app->run();