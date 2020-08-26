<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemNote extends Model
{
    protected $fillable = ['work_item_id', 'note'];

    public function workItem()
    {
    	$this->belongsTo('App\WorkItem');
    }
}
