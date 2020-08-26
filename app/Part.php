<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
	/**
	 * Mass Assignable
	 * @var [type]
	 */
    protected $fillable = ['part_id', 'part', 'price'];

    /**
     * Get associated work items
     * @return [type] [description]
     */
    public function workItems()
    {
    	return $this->belongsToMany('App\WorkItem');
    }

    /**
     * Get associated proposal entries
     * @return [type] [description]
     */
    public function proposalEntries()
    {
        return $this->belongsToMany('App\ProposalEntry')
                    ->withPivot('price', 'quantity');
    }
}
