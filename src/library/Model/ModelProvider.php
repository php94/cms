<?php

declare(strict_types=1);

namespace App\Php94\Cms\Model;

use App\Php94\Cms\Interfaces\ModelInterface;
use Exception;
use PHP94\Event;

class ModelProvider
{
    private $models = [];

    public function __construct()
    {
        Event::dispatch($this);
    }

    public function register(string $model): self
    {
        if (!is_a($model, ModelInterface::class, true)) {
            throw new Exception('模型必须实现接口：' . ModelInterface::class);
        }
        $this->models[$model] = $model;
        return $this;
    }

    public function all()
    {
        return $this->models;
    }
}
