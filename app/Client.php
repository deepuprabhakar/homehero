<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Client extends Model
{
	use SearchableTrait;

	/**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'clients.first_name' => 10,
            'clients.last_name' => 10,
            'clients.city' => 10,
            'clients.state' => 10,
        ]
    ];

    protected $appends = ['name', 'proposal_count'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'home_phone', 'mobile_phone', 'office_phone', 'first_address', 'second_address', 'city', 'zip', 'state', 'email' 
    ];

    /**
     * Get all proposals for this client
     * 
     * @return [Colletion] [Clients]
     */
    public function proposals()
    {
        return $this->hasMany('App\Proposal');
    }

    /**
     * Get fullname
     * @return [type] [description]
     */
    public function getNameAttribute()
    {
        return $this->attributes['first_name']. ' ' . $this->attributes['last_name'];
    }

    public function getProposalCountAttribute()
    {
        $count = 0;
        $proposals = $this->proposals;

        if(count($proposals))
        {
            foreach ($proposals as $key => $proposal) {
                if(count($proposal->staff))
                    if(\Auth::check())
                    if($proposal->staff->first()->id == \Auth::user()->id)
                        $count++;   
            }
        }

        return $count;
    }
}
