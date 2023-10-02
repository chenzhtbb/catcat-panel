<?php

declare(strict_types=1);

namespace catc\panel\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class Tool extends Command
{

    protected $opList;

    public function __construct()
    {
        $this->opList = [
            0 => 'help'
        ];
    }

    protected function configure()
    {
        $this->setName('catc-panel')
            ->addArgument('code', Argument::OPTIONAL, "Operation code")
            ->setDescription('CatC Panel tools');
    }

    protected function execute(Input $input, Output $output)
    {
        $code = trim($input->getArgument('code') ?? '');
        $code = (int)$code ?: 0;

        $output->writeln('' . $code);
    }
}
