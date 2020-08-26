<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraPart extends Model
{
    /**
	 * Mass Assignable
	 * @var [type]
	 */
    protected $fillable = ['part_id', 'part', 'price', 'proposal_entry_id', 'part_desc', 'quantity'];
}
