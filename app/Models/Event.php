<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model 
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'venue', 'lat', 'lon', 'startDate', 'endDate',
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
