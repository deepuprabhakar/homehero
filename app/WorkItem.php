<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkItem extends Model
{
	/**
	 * Mass Assignable
	 * @var [type]
	 */
    protected $fillable = ['type_id', 'sub_type_id', 'detail','item_id', 'price', 'est_hrs'];

    protected $appends = ["type", 'sub_type'];

    /**
     * Get associated parts
     * @return [type] [description]
     */
    public function parts()
    {
    	return $this->belongsToMany('App\Part');
    }

    /**
     * Get associated steps
     * @return [type] [description]
     */
    public function steps()
    {
    	return $this->hasMany('App\ItemStep', 'item_id');
    }

    /**
     * Get associated type
     * @return [type] [description]
     */
    public function itemType()
    {
        return $this->hasOne('App\Type', 'id', 'type_id');
    }

    /**
     * Get associated sub type
     * @return [type] [description]
     */
    public function itemSubType()
    {
        return $this->hasOne('App\SubType', 'id', 'sub_type_id');
    }

    /**
     * Get type name from types table
     * @return [type] [description]
     */
    public function getTypeAttribute()
    {
        return \App\Type::find($this->attributes['type_id'])->type;
    }

    /**
     * Get sub type name from types table
     * @return [type] [description]
     */
    public function getSubTypeAttribute()
    {
        return \App\SubType::find($this->attributes['sub_type_id'])->sub_type;
    }

    public function notes()
    {
        return $this->hasMany('App\ItemNote');
    }
    
}
