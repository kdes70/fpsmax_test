<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
        'id',
        'image_url',
        'name',
        'slug',
        'url',
        'live_supported',
        'modified_at'
    ];


    public function matches()
    {
        return $this->hasMany(Matche::class, 'league_id');
    }
}
