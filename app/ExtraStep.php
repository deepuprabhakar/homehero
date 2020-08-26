<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraStep extends Model
{
    /**
	 * Mass Assignable
	 * @var [type]
	 */
    protected $fillable = ['proposal_entry_id', 'step', 'step_desc', 'step_order'];
}
