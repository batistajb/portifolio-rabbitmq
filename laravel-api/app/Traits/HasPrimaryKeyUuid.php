<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasPrimaryKeyUuid
{
    /**
     *  Setup model event hooks.
     */
    public static function bootHasPrimaryKeyUuid()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    /**
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
