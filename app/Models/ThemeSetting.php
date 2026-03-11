<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model {
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null) {
        $s = static::where('key', $key)->first();
        return $s ? $s->value : $default;
    }

    public static function set($key, $value) {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

public static function getAllForCSS() {
    return static::all()->pluck('value', 'key')->toArray();
}
}