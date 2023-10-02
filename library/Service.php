<?php

declare(strict_types=1);

namespace catc\panel;

use think\Service as BaseService;

class Service extends BaseService
{
    public function register()
    {
        $this->commands([
            'catc-panel'         => 'catc\\panel\\command\\Tool',
            'catc-panel:worker'  => 'catc\\panel\\command\\Worker',
        ]);
    }

    public function boot()
    {
        $this->app->event->listen('HttpRun', function () {
            $this->app->middleware->add(PanelApp::class);
        });
    }
}
