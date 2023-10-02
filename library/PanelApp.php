<?php

declare(strict_types=1);

namespace catc\panel;

use Closure;
use think\App;

class PanelApp
{
    protected $app;

    protected $panelPath;

    protected $panelName;

    public function __construct(App $app)
    {
        $this->app  = $app;
        $this->panelPath = realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        $this->panelName = 'catc-panel';
    }

    /**
     * Panel 应用解析
     * @access public
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $this->setApp($this->panelName);
        return $this->app->middleware->pipeline($this->panelName)
            ->send($request)
            ->then(function ($request) use ($next) {
                return $next($request);
            });
    }

    /**
     * 获取路由目录
     * @access protected
     * @return string
     */
    protected function getRoutePath(): string
    {
        return  $this->panelPath . 'route' . DIRECTORY_SEPARATOR;
    }

    /**
     * 设置应用
     * @param string $appName
     */
    public function setApp(string $appName): void
    {
        $this->app->http->name($appName);

        $appPath = $this->panelPath;

        $this->app->setAppPath($appPath);

        $this->app->setNamespace(__NAMESPACE__ . '\\app');

        if (is_dir($appPath)) {
            $this->app->setRuntimePath($this->app->getRuntimePath() . $appName . DIRECTORY_SEPARATOR);
            $this->app->http->setRoutePath($this->getRoutePath());

            //加载应用
            $this->loadApp($appName, $appPath);
        }
    }

    /**
     * 加载应用文件
     * @param string $appName 应用名
     * @return void
     */
    protected function loadApp(string $appName, string $appPath): void
    {
        if (is_file($appPath . 'common.php')) {
            include_once $appPath . 'common.php';
        }

        $files = [];

        $files = array_merge($files, glob($appPath . 'config' . DIRECTORY_SEPARATOR . '*' . $this->app->getConfigExt()));

        foreach ($files as $file) {
            $this->app->config->load($file, pathinfo($file, PATHINFO_FILENAME));
        }

        if (is_file($appPath . 'event.php')) {
            $this->app->loadEvent(include $appPath . 'event.php');
        }

        if (is_file($appPath . 'middleware.php')) {
            $this->app->middleware->import(include $appPath . 'middleware.php', 'app');
        }

        if (is_file($appPath . 'provider.php')) {
            $this->app->bind(include $appPath . 'provider.php');
        }

        // 加载应用默认语言包
        $this->app->loadLangPack($this->app->lang->defaultLangSet());
    }
}
