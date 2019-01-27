<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = [];

    protected $appends = ['label', 'value'];

    protected $visible = ['id', 'label', 'value'];

    public function getLabelAttribute()
    {
        return $this->title;
    }

    public function getValueAttribute()
    {
        return $this->id;
    }
}
