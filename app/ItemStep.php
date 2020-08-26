<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemStep extends Model
{
	/**
	 * Mass Assignable
	 * @var [type]
	 */
    protected $fillable = ['item_id', 'step_id', 'detail'];

    /**
     * Get Associated Work Item
     * @return [type] [description]
     */
    public function workItem()
    {
    	return $this->belongsTo('App\WorkItem');
    }

    /**
     * Get associated proposal entries
     * @return [type] [description]
     */
    public function proposalEntries()
    {
        return $this->belongsToMany('App\ProposalEntry')
                    ->withPivot('step_order', 'type');
    }
}
