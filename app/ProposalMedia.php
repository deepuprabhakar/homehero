<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProposalMedia extends Model
{
    protected $fillable = ['proposal_id', 'media', 'type', 'description', 'flag'];

    /**
     * Get associated proposal entry
     * @return [type] [description]
     */
    public function proposalEntry()
    {
    	return $this->belongs('App\ProposalEntry');
    }
}
