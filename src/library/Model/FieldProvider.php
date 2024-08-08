<?php

declare(strict_types=1);

namespace App\Php94\Cms\Model;

use App\Php94\Cms\Interfaces\FieldInterface;
use Exception;
use PHP94\Event;

class FieldProvider
{
    private $fields = [];

    public function __construct()
    {
        Event::dispatch($this);
    }

    public function register(string $field): self
    {
        if (!is_a($field, FieldInterface::class, true)) {
            throw new Exception('字段必须实现接口：：' . FieldInterface::class);
        }
        $this->fields[$field] = $field;
        return $this;
    }

    public function all()
    {
        return $this->fields;
    }
}
