<?php

declare(strict_types=1);

namespace App\Php94\Cms\Model;

use App\Php94\Cms\Interfaces\ModelInterface;

class BaseModel implements ModelInterface
{
    public static function getTitle(): string
    {
        return '基本数据模型';
    }

    public static function onCreate(array $model)
    {
    }
}
