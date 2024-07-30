<?php

declare(strict_types=1);

namespace App\Php94\Cms\Interfaces;

interface ModelInterface
{
    public static function getTitle(): string;

    public static function onCreate(array $model);
}
