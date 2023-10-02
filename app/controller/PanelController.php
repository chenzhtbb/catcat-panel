<?php

declare(strict_types=1);

namespace catc\panel\app\controller;

use catc\panel\app\model\User;
use think\App;

class PanelController
{

    protected $app;

    protected $mime = [
        'html' => 'text/html',
        'js'   => 'text/javascript',
        'css'  => 'text/css',
        'ico'  => 'image/x-icon',
        'bmp'  => 'image/bmp',
        'gif'  => 'image/gif',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'svg'  => 'image/svg+xml',
    ];

    protected $ext;

    protected $defaultIndex = 'index.html';

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->ext = $this->app->request->ext();
    }

    public function admin($name, $path = 'index')
    {
        $user = User::findOrEmpty(1);
        if ($user->isEmpty() || $user->pathname != $name) {
            return $this->miss();
        }
        // 获取本地文件
        $file = panel_static_path() . "$path.$this->ext";
        // URL 不存在时，访问入口
        if (!is_file($file)) {
            $file = panel_static_path() . "admin/$this->defaultIndex";
        }
        ob_get_clean();
        $content = str_replace('__PANEL__', "$name/admin", file_get_contents($file));
        return response($content)->contentType($this->getContentType());
    }

    public function getContentType()
    {
        return array_key_exists($this->ext, $this->mime) ? $this->mime[$this->ext] : 'text/html';
    }

    public function miss()
    {
        $file = root_path() . 'view' . DIRECTORY_SEPARATOR . '404.html';
        if (!is_file($file)) {
            $file = panel_static_path() . '404.html';
        }
        return response(file_get_contents($file))->contentType($this->getContentType());
    }
}
