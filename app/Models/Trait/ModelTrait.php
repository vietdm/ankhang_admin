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
}
