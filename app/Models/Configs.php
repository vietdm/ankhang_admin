<?php

namespace App\Models;

use App\Helpers\Format;
use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configs extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'configs';

    public static function get($name, $default = null, $format = Format::String): mixed
    {
        $config = self::whereName($name)->first();
        if (!$config) return $default;
        return match ($format) {
            Format::Boolean => $config->value === '1',
            Format::Double => (float)$config->value,
            Format::Integer => (int)$config->value,
            default => $config->value,
        };
    }

    public static function getDouble($name, $default = null)
    {
        return self::get($name, $default, Format::Double);
    }

    public static function getInt($name, $default = null)
    {
        return self::get($name, $default, Format::Integer);
    }

    public static function getBoolean($name, $default = null)
    {
        return self::get($name, $default, Format::Boolean);
    }

    public static function set($name, $value, $format = Format::String): Configs
    {
        $value = match ($format) {
            Format::Boolean => $value === true ? '1' : '0',
            Format::Double, Format::Integer => strval($value),
            default => $value,
        };
        $config = self::whereName($name)->first();
        if (!$config) {
            $config = new self;
            $config->name = $name;
        }
        $config->value = $value;
        $config->save();
        return $config;
    }

    public static function setDouble($name, $value)
    {
        return self::set($name, $value, Format::Double);
    }

    public static function setInt($name, $value)
    {
        return self::set($name, $value, Format::Integer);
    }
}
