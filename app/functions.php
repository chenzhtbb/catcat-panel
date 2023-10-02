<?php

declare(strict_types=1);

const PANEL_LOGIN_NAME = 'PANEL_LOGIN_NAME';

function sqlite_path()
{
    return 'sqlite:' . panel_path() . 'library' . DIRECTORY_SEPARATOR . 'panel.db';
}

function panel_path()
{
    return realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
}

function panel_static_path()
{
    return panel_path() . 'public' . DIRECTORY_SEPARATOR;
}
