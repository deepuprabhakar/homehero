<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = ['client_id', 'first_name', 'last_name', 'phone', 'address', 'job_address', 'grand_total', 'net_total', 'discount'];

    protected $appends = ["name", "labour_cost"];

    public $labour_cost;

    /**
     * Get all staff related to this proposal
     *
     * @return [Colletion] [Clients]
     */
    public function staff()
    {
    	return $this->belongsToMany('App\Staff');
    }

    /**
     * Get all proposal entries
     *
     * @return [Colletion] [Clients]
     */
    public function proposalEntries()
    {
        return $this->hasMany('App\ProposalEntry');
    }

    /**
     * Get client
     *
     * @return [Colletion] [Clients]
     */
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    /**
     * Get client fullname
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * Get total amount
     * @return [type] [description]
     */
    public function getGrandTotalAttribute()
    {
        $sum = 0;
        $this->labour_cost = 0;

        // $sum += $this->proposalEntries->sum('list_price');

        foreach ($this->proposalEntries as $entry) {

            if($entry->list_price == $entry->extended_price || $entry->extended_price == 0)
            {   
                $sum += $entry->list_price;
                $this->labour_cost += $entry->list_price;
            }    
            else
            {    
                $sum += $entry->extended_price;
                $this->labour_cost += $entry->extended_price;
            }    

            if($entry->parts->count() > 0)
            {
                foreach ($entry->parts as $part)
                {
                    if($part->pivot->price != 0)
                        $sum += $part->pivot->price * $part->pivot->quantity;
                    else
                        $sum += $part->price * $part->pivot->quantity;
                }
            }

            if($entry->extraParts->count() > 0)
                foreach ($entry->extraParts as $part)
                    $sum += $part->price * $part->quantity;

        }

        return $sum;
    }

    public function versions()
    {
        return $this->hasMany('App\ProposalVersion', 'proposal_id');
    }

    public function getLabourCostAttribute()
    {
        return $this->labour_cost;
    }

    public function getNetTotalAttribute()
    {
        $grand_total = $this->getGrandTotalAttribute();
        $discount = $this->getLabourCostAttribute() * ($this->discount/100);
        return $grand_total - $discount;
    }
}
