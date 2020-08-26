<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubType extends Model
{
    protected $fillable = ['type_id', 'sub_type'];

    /**
     * Get associated type
     * @return [type] [description]
     */
    public function getType()
    {
    	return $this->belongsTo('App\Type', 'type_id');
    }
}
