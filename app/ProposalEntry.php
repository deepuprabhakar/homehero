<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProposalEntry extends Model
{
    protected $fillable = ['proposal_id', 'location_id', 'room_id', 'work_item_id', 'list_price', 'extended_price', 'notes', 'task_local_id'];

    // protected $appends = ['sorted_steps'];

    /**
     * Get associated proposal
     *
     * @return Proposal
     */
    public function proposal()
    {
        return $this->belongsTo('App\Proposal');
    }

    /**
     * Get associated location
     *
     * @return location
     */
    public function location()
    {
        return $this->hasOne('App\Location', 'id', 'location_id');
    }

    /**
     * Get associated room
     *
     * @return room
     */
    public function room()
    {
        return $this->hasOne('App\Room', 'id', 'room_id');
    }

    /**
     * Get associated work-item
     *
     * @return work-item
     */
    public function workItem()
    {
        return $this->belongsTo('App\WorkItem');
    }

    /**
     * Get associated parts
     * @return [type] [description]
     */
    public function parts()
    {
        return $this->belongsToMany('App\Part')
                    ->withPivot('price', 'quantity');
    }

    /**
     * Get associated extra parts
     * @return [type] [description]
     */
    public function extraParts()
    {
        return $this->hasMany('App\ExtraPart');
    }

    /**
     * Get associated steps
     * @return [type] [description]
     */
    public function steps()
    {
        return $this->belongsToMany('App\ItemStep')
                    ->withPivot('step_order', 'type');
    }

    /**
     * Get associated extra parts
     * @return [type] [description]
     */
    public function extraSteps()
    {
        return $this->hasMany('App\ExtraStep');
    }

    /**
     * Get associated media
     * @return [type] [description]
     */
    public function media()
    {
        return $this->hasMany('App\ProposalMedia');
    }

    public function sortedSteps()
    {
        $task_steps = [];
        $main_steps = [];
        $key = 0;

        if(count($this->steps()->get()) > 0)
        foreach ($this->steps()->orderBy('step_order')->get() as $step) {
            
            $task_steps[$key]['item_step_id'] = $step->pivot->item_step_id;
            $task_steps[$key]['step'] = $step->detail;
            $task_steps[$key]['step_order'] = $step->pivot->step_order;
            $task_steps[$key]['type'] = $step->pivot->type;
            $task_steps[$key]['step_desc'] = '';
            $main_steps[] = $step->pivot->item_step_id;
            $key++;
        }
        if(count($this->extraSteps()->get()) > 0)
        foreach ($this->extraSteps()->orderBy('step_order')->get() as $step) {
            
            $task_steps[$key]['item_step_id'] = $step->id;
            $task_steps[$key]['step'] = $step->step;
            $task_steps[$key]['step_order'] = $step->step_order;
            $task_steps[$key]['type'] = $step->type;
            $task_steps[$key]['step_desc'] = $step->step_desc;
            $key++;
        }

        if(count($task_steps) > 0)
        {
            $sort_col = [];
            foreach ($task_steps as $key => $row) {
                $sort_col[$key] = $row['step_order'];
            }

            array_multisort($sort_col, SORT_ASC, $task_steps);
        }

        return $task_steps;
    }
}
