<?php

declare(strict_types=1);

use think\facade\Route;

Route::group('api', function () {
});

Route::get('<name>/<path?>', 'Panel/admin')->pattern([
    'name' => '[a-zA-Z0-9]+',
    'path' => '[\/\-\w]+'
]);

Route::miss('Panel/miss');
