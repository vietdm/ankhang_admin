<?php

namespace App\Models\Trait;

trait ModelTrait
{
    public static function insert($params)
    {
        $self = new self;
        foreach ($params as $key => $value) {
            $self->{$key} = $value;
        }
        $self->save();
        return $self;
    }

    public function json($columns = [], $isIgnore = false) {
        if ($isIgnore) return $this->jsonWithIgnoreColumn($columns);
        return $this->jsonWithSelectColumn($columns);
    }

    public function jsonWithIgnoreColumn($columns = []) {
        $data = $this->toArray();
        foreach ($columns as $column) {
            unset($data[$column]);
        }
        return json_encode($data);
    }

    public function jsonWithSelectColumn($columns = []) {
        $data = $this->toArray();
        $result = [];
        foreach ($columns as $column) {
            if (!isset($data[$column])) {
                continue;
            }
            $result[$column] = $data[$column];
        }
        return json_encode($result);
    }
}
