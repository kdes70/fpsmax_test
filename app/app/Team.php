<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'id',
        'acronym',
        'image_url',
        'name',
        'slug',
    ];

    public function matches()
    {
        return $this->belongsToMany(Matche::class, 'opponents', 'team_id', 'match_id');
    }
}
