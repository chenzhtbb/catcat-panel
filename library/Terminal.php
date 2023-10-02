<?php

declare(strict_types=1);

namespace catc\panel\library;

class Terminal
{

    static protected $instance = null;

    protected $commands = [
        'admin' => 'npm run build --base=/%s/'
    ];

    protected $descriptorSpec = [];

    protected $output = null;


    static public function make()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $outpue_dir = runtime_path('terminal');
        if (!is_dir($outpue_dir)) {
            mkdir($outpue_dir, 0777, true);
        }
        $this->output = runtime_path('terminal') . 'exec.log';
        file_put_contents($this->output, '');
        $this->descriptorSpec = [
            // stdin
            0 => ['pipe', 'r'],
            // stdout
            1 => ['pipe', 'w'], //['file', $this->output, 'w'],
            // stderr
            2 => ['pipe', 'w'], //['file', $this->output, 'w'],
        ];
    }

    public function exec(string $command, array $argument = [])
    {
        if (!array_key_exists($command, $this->commands)) {
            return false;
        }
        // sprintf($this->commands[$command], 'abc')
        $pwd = root_path('extend') . 'catc' . DIRECTORY_SEPARATOR . 'panel' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'panel-admin';
        $process = proc_open(sprintf($this->commands[$command], 'abc'), $this->descriptorSpec, $pipes, $pwd);
        if (!is_resource($process)) {
            return false;
        }
        while ($this->getProcStatus($process)) {
            $content = stream_get_contents($pipes[1]);
            // $content = file_get_contents($this->output);
            dump ($this->filter($content));
            usleep(500000);
        }
        // for ($i = 0; $i < 10; $i++) {
        //     dump(proc_get_status($process));
        //     $content = stream_get_contents($pipes[1]);
        //     dump(mb_convert_encoding($content, 'UTF-8', ['UTF-8', 'GBK', 'GB2312', 'BIG5']));
        //     usleep(500000);
        // }
        foreach ($pipes as $pipe) {
            fclose($pipe);
        }
        proc_close($process);
    }

    public function getProcStatus($process): bool
    {
        return proc_get_status($process)['running'];
    }

    public function filter($str): string
    {
        // 去除左右空格
        $str  = trim($str);
        // 替换换行符
        $str  = str_replace(['\r\n', '\r'], '\n', $str);
        // 去除控制字符
        $str  = str_replace('', '', $str);
        return mb_convert_encoding($str, 'UTF-8', ['UTF-8', 'GBK', 'GB2312', 'BIG5']);
    }
}
