<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['type'];

    /**
     * Get all sub types
     * @return [type] [description]
     */
    public function subTypes()
    {
    	return $this->hasMany('App\SubType');
    }
}
