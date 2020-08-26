<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['type', 'sub_type', 'loc_id'];

    /*protected $appends = ["location"];

    public function getLocationAttribute()
    {
    	return $this->attributes['sub_type'] . '(' . $this->attributes['type'] . ')';
    }*/
}
