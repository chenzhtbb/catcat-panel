<?php

declare(strict_types=1);

namespace catc\panel\library;

use think\Model as BaseModel;
use think\model\concern\SoftDelete;

class Model extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
}
