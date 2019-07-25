<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matche extends Model
{
    protected $fillable = [
        'id',
        'league_id',
        'name',
        'slug',
        'status',
        'match_type',
        'number_of_games',
        'draw',
        'end_at',
        'forfeit',
        'winner',
        'winner_id',
        'modified_at',
        'begin_at'
    ];

    protected $casts = [
        'draw'    => 'boolean',
        'forfeit' => 'boolean',
    ];

    protected $dates = [
        'begin_at',
        'modified_at',
    ];

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'opponents', 'match_id', 'team_id');
    }
}
