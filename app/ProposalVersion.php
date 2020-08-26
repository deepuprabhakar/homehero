<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProposalVersion extends Model
{
    protected $fillable = [
    	'title', 'remarks', 'proposal_id', 'file', 'sent', 'approved',
    ];

    public function proposal()
    {
    	return $this->belongsTo('App\Proposal');
    }
}
