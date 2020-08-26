@extends('admin.app')

@section('title')
    Edit Proposal
@endsection

@push('styles')
{!! Html::style('public/css/animate.css') !!}
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container">
    <div class="row">
        @include('admin.partials.sidebar')
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit Proposal
                    <div class="pull-right">
                        <a href={{ route('admin.proposals.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                    <div class="pull-right">
                        <a href={{ route('proposals.versions', $proposal) }} class="btn btn-info admin-add-button">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Versions 
                        ({{ $proposal->versions->count() }})
                        </a>
                    </div>
                    <div class="pull-right">
                        <a href={{ route('admin.proposals.show', $proposal) }} class="btn btn-success admin-add-button">
                        <i class="fa fa-eye" aria-hidden="true"></i> View 
                        
                        </a>
                    </div>
                </div>

                {!! Form::model($proposal, 
                            ['url' => route('admin.proposals.update', $proposal->id), 
                            'id' => 'proposal-form', 
                            'method' => 'PATCH']) !!}

                <div class="panel-body">
                    
                    <div class="well well-sm" style="margin-bottom: 15px;">
                        {{-- Proposal Details Table --}}
                        <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th colspan="2">Client Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Name:</td>
                                    <td>{{ $proposal->name }}</td>
                                </tr>
                                <tr>
                                    <td>Phone:</td>
                                    <td>{{ $proposal->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Address:</td>
                                    <td>{{ $address }}</td>
                                </tr>
                                <tr>
                                    <td>Job Address:</td>
                                    <td>{{ $job_address }}</td>
                                </tr>
                                <tr>
                                    <td>Date:</td>
                                    <td>{{ $proposal->created_at  }}</td>
                                </tr>

                            </tbody>
                        </table>
                        </div>
                    </div>

                    <div class="well well-sm" style="margin-bottom: 0;">

                        <p class="alert bg-primary title-box">
                            Tasks - <span class="badge square-badge">{{ $proposal->proposalEntries->count() }}</span>
                            <a href="#add-task" 
                                class="pull-right add-task-link btn btn-info btn-sm"
                                data-toggle="modal" 
                                data-target="#new-task-modal">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add Task</a>
                        </p>
                        
                        @if($errors->count() > 0)
                            <div class="alert alert-danger fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                You have some errors. Please fill all input fields to avoid these errors. You can remove fields which are marked removable.    
                            </div>
                        @endif

                        @if(Session::has('updated'))
                            <div class="alert alert-success fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                {{ Session::get('updated') }}  
                            </div>
                        @endif

                        @if(Session::has('task_error'))
                            <div class="alert alert-danger fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                {{ Session::get('task_error') }}  
                            </div>
                        @endif

                        <div class="panel-group" id="accordion">

                        @foreach ($proposal->proposalEntries as $key => $task)
                        
                            <div class="panel panel-info">
                                
                                <div class="panel-heading" style="position: relative;">
                                  <h4 class="panel-title task-title">
                                
                                    <a class="accordion-toggle" 
                                        data-toggle="collapse" 
                                        data-parent="#accordion" 
                                        href="#collapse{{ $task->id }}">
                                        {{ $key+1 }} - 
                                        {{ $task->workItem->detail . ' - ' 
                                            . $task->workItem->itemType->type }}
                                    </a>
                                    <i class="indicator glyphicon glyphicon-chevron-up pull-right"></i>
                                
                                  </h4>
                                    <a href="{{ route('task.remove', $task->id) }}" 
                                        class="btn btn-danger btn-xs task-remove-button">
                                        <i class="fa fa-minus-circle"></i> Remove</a>
                                </div>
                                
                                <div id="collapse{{ $task->id }}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="location">Location</label>
                                                    {!! Form::select("tasks[$task->id][location_id]", 
                                                        [
                                                            null => 'Select location',
                                                        ] + $locations, 
                                                        $task->location_id, 
                                                        ['id' => 'location', 
                                                        'class' => 'form-control select-location',
                                                        'style' => 'width: 100%']) !!}
                                                    
                                                    <span class="form-error">
                                                        
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="room">Room</label>
                                                    {!! Form::select("tasks[$task->id][room_id]", 
                                                        [
                                                            null => 'Select room',
                                                        ] + \App\Room::orderBy('area')->pluck('area', 'id')->toArray(), 
                                                        $task->room_id, 
                                                        ['id' => 'room', 
                                                        'class' => 'form-control select-room',
                                                        'style' => 'width: 100%']) !!}
                                                    
                                                    <span class="form-error">
                                                       
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="type">Type</label>
                                                    {!! Form::select("tasks[$task->id][type]", 
                                                        [
                                                            null => 'Select type',
                                                        ] + \App\Type::orderBy('type')->pluck('type', 'id')->toArray(), 
                                                        $task->workItem->type_id, 
                                                        ['id' => "type_id-$task->id", 
                                                        'class' => 'form-control select-type',
                                                        'data-target' => "#sub_type-$task->id",
                                                        'data-target-reset' => ".reset-$task->id",
                                                        'data-task-id' => $task->id,
                                                        'style' => 'width: 100%']) !!}
                                                    
                                                    <span class="form-error">
                                                    
                                                    </span>
                                                </div>
                                            </div>
                    
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sub_type">Sub Type</label>
                                                    {!! Form::select("tasks[$task->id][sub_type]", 
                                                        [
                                                            null => 'Select Sub Type',
                                                        ] + \App\SubType::where('type_id', $task->workItem->type_id)->orderBy('sub_type')->pluck('sub_type', 'id')->toArray(), 
                                                        $task->workItem->sub_type_id, 
                                                        ['id' => "sub_type-$task->id", 
                                                        'class' => "form-control select-sub-type reset-$task->id",
                                                        'style' => 'width: 100%',
                                                        'data-task-id' => "$task->id"]) !!}
                                                    
                                                    <span class="form-error"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="work_item">Work Item</label>
                                                    {!! Form::select("tasks[$task->id][work_item_id]", 
                                                        [
                                                            null => 'Select Work Item',
                                                        ] + 
                                                            \App\WorkItem::
                                                            where('type_id', $task->workItem->type_id)
                                                            ->where('sub_type_id', $task->workItem->sub_type_id)
                                                            ->pluck('detail', 'id')
                                                            ->toArray(),
                                                        $task->work_item_id, 
                                                        ['id' => "work_item-$task->id", 
                                                        'class' => "form-control select-work-item reset-$task->id",
                                                        'style' => 'width: 100%',
                                                        'data-task-id' => "$task->id",
                                                        'placeholder' => 'Select Work Item']) !!}
                                                    
                                                    <span class="form-error">
                                                    
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="list-price-{{ $task->id }}">List Price</label>
                                                {!! Form::text("tasks[$task->id][list_price]",
                                                    $task->list_price,
                                                    [
                                                        'class' => "form-control reset-$task->id",
                                                        'placeholder' => 'Enter list price',
                                                        'id' => "list-price-$task->id"
                                                    ]   
                                                ) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="extended-price-{{ $task->id }}">Extended Price</label>
                                                {!! Form::text("tasks[$task->id][extended_price]",
                                                    $task->extended_price,
                                                    [
                                                        'class' => "form-control reset-$task->id",
                                                        'placeholder' => 'Enter extended price',
                                                        'id' => "extended-price-$task->id"
                                                    ]   
                                                ) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-12" style="margin-bottom: 18px;">
                                                <div style="position:relative">
                                                    <label for="">
                                                        Parts & Price
                                                        {{-- <span class="badge square-badge">
                                                            {{ $task->parts->count() }}
                                                        </span> --}}
                                                    </label>
                                                    <a href="#part" class="btn btn-primary btn-xs add-parts-button"
                                                    data-target="#main-parts-{{ $task->id }}"
                                                    data-count="{{ $task->parts->count() }}"
                                                    data-task-id="{{ $task->id }}" 
                                                    title="Add Parts" >
                                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                                        Add Parts
                                                    </a>
                                                </div>

                                            {{-- @if(count($task->parts) == 0)
                                                <div class="well well-sm">
                                                <div class="text-muted">No parts added.</div>
                                                </div>    
                                            @endif --}}

                                            @if(count($task->parts) == 0)
                                                <div class="row" style="width: 98%; display: none;" id="part-label">
                                            @else
                                                <div class="row" style="width: 98%;" id="part-label">
                                            @endif
                                                <div class="col-md-4">
                                                    <span class="label label-default">Part</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <span class="label label-default">Unit Price</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <span class="label label-default">Quantity</span>
                                                </div>
                                            </div>

                                            @foreach ($task->parts as $key => $part)
                                            
                                            <div style="position: relative">
                                                
                                                <div class="panel panel-default extra-part-panel clear-{{ $task->id }}" style="width: 95%;">
                                                  <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                        
                                                        {!! Form::select("tasks[$task->id][parts][$key][part_id]", 
                                                                    \App\Part::orderBy('part')->pluck('part', 'id')->toArray(), 
                                                                    $part->id,
                                                                    ['id' => "parts-$task->id", 
                                                                    'class' => "form-control select-parts parts-$task->id reset-$task->id",
                                                                    'style' => 'width: 100%']) !!}
                                                        </div>
                                                        
                                                        <div class="col-md-4 div-part-price">
                                                            
                                                            @if($part->pivot->price == 0)
                                                            {!! Form::text(
                                                                "tasks[$task->id][parts][$key][price]",
                                                                $part->price,
                                                                [
                                                                    'class' => "form-control part-input reset-$task->id",
                                                                    'placeholder' => 'Part unit price',
                                                                    'readonly'
                                                                ]
                                                            )!!}
                                                            @else
                                                            {!! Form::text(
                                                                "tasks[$task->id][parts][$key][price]",
                                                                $part->pivot->price,
                                                                [
                                                                    'class' => "form-control part-input reset-$task->id",
                                                                    'placeholder' => 'Part unit price',
                                                                    'readonly'
                                                                ]
                                                            )!!}
                                                            @endif
                                                        </div>

                                                        <div class="col-md-4">
                                                            
                                                            {!! Form::text(
                                                                "tasks[$task->id][parts][$key][quantity]",
                                                                $part->pivot->quantity,
                                                                [
                                                                    'class' => "form-control part-quantity reset-$task->id",
                                                                    'placeholder' => 'Part quantity',
                                                                    
                                                                ]
                                                            ) !!}
                                                        </div>    

                                                    </div>
                                                  </div>
                                                </div>

                                                <a href="#remove" 
                                                    class="button-right-add remove-extra-parts"
                                                    title="Remove">
                                                    <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>
                                                </a>

                                            </div>

                                            @endforeach

                                            <div id="main-parts-{{ $task->id }}"></div>
                                            
                                            </div>
                                            
                                            <div class="col-md-12">

                                                <div class="form-group" style="position: relative;" class="div-parent">
                                                    <label for="parts">
                                                        Extra Parts 
                                                        {{-- <span class="badge square-badge">
                                                            {{ $task->extraParts->count() }}
                                                        </span> --}}
                                                        <a href="#extra-part" 
                                                        class="btn btn-primary btn-xs add-parts"
                                                        data-target="#custom-parts-{{ $task->id }}"
                                                        data-count="{{ $task->extraParts->count() }}"
                                                        data-task-id="{{ $task->id }}" 
                                                        title="Add Parts" >
                                                            <i class="fa fa-plus" aria-hidden="true"></i> 
                                                            Add Extra Parts
                                                        </a>
                                                    </label>
                                                
                                                @if(count($task->extraParts) == 0)
                                                    <div class="row" style="width: 98%; display: none;" id="extra-part-label">
                                                @else
                                                    <div class="row" style="width: 98%;" id="extra-part-label">
                                                @endif
                                                        <div class="col-md-4">
                                                            <span class="label label-default">Extra Part</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <span class="label label-default">Unit Price</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <span class="label label-default">Quantity</span>
                                                        </div>
                                                    </div>
                                                    
                                                    @foreach ($task->extraParts as $key => $part)

                                                    <div style="position: relative">
                                                        {{ Form::hidden("tasks[$task->id][extra_parts][$key][id]", $part->id, ['class' => "reset-$task->id hidden-part-$task->id"]) }}
                                                        <div class="panel panel-default extra-part-panel clear-{{ $task->id }}" style="width: 95%">
                                                          <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    {{ Form::text(
                                                                        "tasks[$task->id][extra_parts][$key][part]",
                                                                        $part->part,
                                                                        [
                                                                            'class' => "form-control reset-$task->id",
                                                                            'placeholder' => 'Enter part name'
                                                                        ]
                                                                    )}}
                                                                    
                                                                </div>
                                                                <div class="col-md-4">
                                                                    {{ Form::text(
                                                                        "tasks[$task->id][extra_parts][$key][price]",
                                                                        $part->price,
                                                                        [
                                                                            'class' => "form-control reset-$task->id",
                                                                            'placeholder' => 'Enter part unit price'
                                                                        ]
                                                                    )}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    {{ Form::text(
                                                                        "tasks[$task->id][extra_parts][$key][quantity]",
                                                                        $part->quantity,
                                                                        [
                                                                            'class' => "form-control reset-$task->id",
                                                                            'placeholder' => 'Enter part quantity'
                                                                        ]
                                                                    )}}
                                                                </div>
                                                                <div class="col-md-12">
                                                                    {{ Form::text(
                                                                        "tasks[$task->id][extra_parts][$key][part_desc]",
                                                                        $part->part_desc,
                                                                        [
                                                                            'class' => "form-control reset-$task->id",
                                                                            'placeholder' => 'Enter part description',
                                                                            'style' => 'margin-top: 5px;'
                                                                        ]
                                                                    )}}
                                                                </div>
                                                            </div>
                                                          </div>
                                                        </div>

                                                        <a href="#remove" 
                                                            class="button-right-add remove-extra-parts"
                                                            title="Remove">
                                                            <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>
                                                        </a>

                                                    </div>
                                                    
                                                    @endforeach
                                                    <div id="custom-parts-{{ $task->id }}"></div>   

                                                </div>
                                            </div>    
                                            
                                            {{-- steps calculations --}}
                                            @php
                                            $task_steps = [];
                                            $main_steps = [];
                                            $key = 0;

                                            if(count($task->steps) > 0)
                                            foreach ($task->steps()->orderBy('step_order')->get() as $step) {
                                                
                                                $task_steps[$key]['item_step_id'] = $step->pivot->item_step_id;
                                                $task_steps[$key]['step'] = $step->detail;
                                                $task_steps[$key]['step_order'] = $step->pivot->step_order;
                                                $task_steps[$key]['type'] = $step->pivot->type;
                                                $task_steps[$key]['step_desc'] = '';
                                                $main_steps[] = $step->pivot->item_step_id;
                                                $key++;
                                            }
                                            if(count($task->extraSteps) > 0)
                                            foreach ($task->extraSteps()->orderBy('step_order')->get() as $step) {
                                                
                                                $task_steps[$key]['item_step_id'] = $step->id;
                                                $task_steps[$key]['step'] = $step->step;
                                                $task_steps[$key]['step_order'] = $step->step_order;
                                                $task_steps[$key]['type'] = $step->type;
                                                $task_steps[$key]['step_desc'] = $step->step_desc;
                                                $key++;
                                            }

                                            /*function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) 
                                            {
                                                $sort_col = array();
                                                foreach ($arr as $key=> $row) {
                                                    $sort_col[$key] = $row[$col];
                                                }

                                                array_multisort($sort_col, $dir, $arr);
                                            }*/

                                            // array_sort_by_column($task_steps, 'step_order');

                                            // Sort steps order array
                                            if(count($task_steps) > 0)
                                            {
                                                $sort_col = [];
                                                foreach ($task_steps as $key => $row) {
                                                    $sort_col[$key] = $row['step_order'];
                                                }

                                                array_multisort($sort_col, SORT_ASC, $task_steps);
                                            }

                                            @endphp

                                            <div class="col-md-12">
                                            <div class="form-group">
                                            
                                            @if(count($task_steps) > 0)
                                                <label for="steps" id="label-steps" style="display: block;">
                                            @else
                                                <label for="steps" id="label-steps" style="display: none;">
                                            @endif
                                                    Steps - 
                                                    <span class="text-muted">( * Extra added steps )</span>
                                                </label>
                                            

                                                <div id="steps-container">
                                                    <ul class="list-group list-group-sortable list-group-{{ $task->id }}">
                                                    @foreach ($task_steps as $index => $step)
                                                    @php
                                                        $array_key = uniqid(time());
                                                    @endphp
                                                    <li class="list-group-item" id="step-li-{{ $step['item_step_id'] }}">
                                                        <div style="position: relative;">
                                                        <div class="tags">
                                                        @if($step['type'] == 'extra_step')
                                                            *{{ $step['step'] }}
                                                        @else
                                                            {{ $step['step'] }}
                                                        @endif
                                                            {!! Form::hidden("tasks[$task->id][steps_new][$array_key][item_step_id]",
                                                                      $step['item_step_id'],
                                                                      ['class' => 'item_step_id']  
                                                            ) !!}
                                                            {!! Form::hidden("tasks[$task->id][steps_new][$array_key][step]",
                                                                      $step['step'],
                                                                      ['class' => 'step']  
                                                            ) !!}
                                                            {!! Form::hidden("tasks[$task->id][steps_new][$array_key][step_order]",
                                                                      $step['step_order'],
                                                                      ['class' => 'step_order']  
                                                            ) !!}
                                                            {!! Form::hidden("tasks[$task->id][steps_new][$array_key][type]",
                                                                      $step['type'],
                                                                      ['class' => 'type']  
                                                            ) !!}
                                                            {!! Form::hidden("tasks[$task->id][steps_new][$array_key][step_desc]",
                                                                      $step['step_desc'],
                                                                      ['class' => 'step_desc']  
                                                            ) !!}
                                                        </div>
                                                        <a href="#remove" 
                                                            class="button-right-add remove-steps"
                                                            title="Remove"
                                                            data-item-id="{{ $step['item_step_id'] }}"
                                                            data-target="#step-li-{{ $step['item_step_id'] }}">
                                                            <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>
                                                        </a>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                    </ul>
                                                </div> <!-- Steps container-->

                                            </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group" style="position: relative;">
                                                    <label for="steps">Add Step
                                                    <a href="#extra-step" 
                                                        class="btn btn-primary btn-xs add-steps"
                                                        data-target=".steps-{{ $task->id }}"
                                                        data-count="{{ count($task_steps) }}"
                                                        data-task-id="{{ $task->id }}" 
                                                        title="Add Step">
                                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                                        Add Step
                                                    </a>
                                                    </label>
                                                    {!! Form::select("tasks[$task->id][steps][]", 
                                                        [ null=>'Select' ]
                                                        +$task->workItem->steps()->pluck('detail', 'id')->toArray()
                                                        , 
                                                        null,
                                                        ['id' => 'steps', 
                                                        'class' => "form-control select-steps reset-$task->id steps-$task->id",
                                                        'style' => 'width: 100%']) !!}
                                                    {{-- <a href="#extra-step" 
                                                        class="button-right-add add-steps"
                                                        data-target=".steps-{{ $task->id }}"
                                                        data-count="{{ count($task_steps) }}"
                                                        data-task-id="{{ $task->id }}" 
                                                        title="Add Extra Steps">
                                                        <i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i>
                                                    </a> --}}
                                                    {{ ($errors->has('steps') ? $errors->first('steps') : '') }}
                                                    <span class="form-error"></span>
                                                </div>
                                                <div class="form-group" id="toggle-new-step-inputs">
                                                    <label for="">Add Extra Step
                                                    <a href="#add-extra-step" 
                                                        class="btn btn-primary btn-xs add-extra-step"
                                                        id="add-extra-step"
                                                        data-task-id="{{ $task->id }}" 
                                                        data-count="{{ count($task_steps) }}" 
                                                        title="Add Extra Step">
                                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                                        Add Extra Step
                                                    </a>        
                                                    </label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            {!! Form::text('new_step', null, 
                                                                [
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Enter extra step',
                                                                    'id' => "new-step-$task->id"
                                                                ]
                                                            ) !!}
                                                        </div>
                                                        <div class="col-md-6">
                                                            {!! Form::text('new_step_desc', null, 
                                                                [
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Enter step description',
                                                                    'id' => "new-step-desc-$task->id"
                                                                ]
                                                            ) !!}
                                                        </div>
                                                        {{-- <div class="col-md-2">
                                                            <a href="#add-to-steps" 
                                                                class="btn btn-primary form-control add-extra-step"
                                                                id="add-extra-step"
                                                                data-task-id="{{ $task->id }}" 
                                                                data-count="{{ count($task_steps) }}">
                                                                Add
                                                            </a>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>

                                           {{--  <div class="col-md-12">
                                                <div class="form-group" style="position: relative;">
                                                    <label for="parts">
                                                        Extra Steps - 
                                                        <span class="badge square-badge">
                                                            {{ $task->extraSteps->count() }}
                                                        </span>
                                                        <a href="#extra-step" 
                                                        class="btn btn-primary btn-xs add-steps"
                                                        data-target="#custom-steps-{{ $task->id }}"
                                                        data-count="{{ $task->extraSteps->count() }}"
                                                        data-task-id="{{ $task->id }}" 
                                                        title="Add Steps" >
                                                            <i class="fa fa-plus" aria-hidden="true"></i> 
                                                            Add Extra Steps
                                                        </a>
                                                    </label>
                                                    
                                                    @foreach ($task->extraSteps as $key => $step)

                                                    <div style="position: relative">
                                                        {{ Form::hidden("tasks[$task->id][extra_steps][$key][id]", $step->id, ['class' => "reset->$task->id"]) }}
                                                        <div class="panel panel-default extra-part-panel" style="width: 95%">
                                                          <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    {{ Form::text(
                                                                        "tasks[$task->id][extra_steps][$key][step]",
                                                                        $step->step,
                                                                        [
                                                                            'class' => "form-control reset-$task->id",
                                                                            'placeholder' => 'Enter step'
                                                                        ]
                                                                    )}}
                                                                    
                                                                </div>
                                                                <div class="col-md-6">
                                                                    {{ Form::text(
                                                                        "tasks[$task->id][extra_steps][$key][step_desc]",
                                                                        $step->step_desc,
                                                                        [
                                                                            'class' => "form-control reset-$task->id",
                                                                            'placeholder' => 'Enter step description'
                                                                        ]
                                                                    )}}
                                                                    
                                                                </div>
                                                            </div>
                                                          </div>
                                                        </div>

                                                        <a href="#remove" 
                                                            class="button-right-add remove-extra-parts"
                                                            title="Remove">
                                                            <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>
                                                        </a>

                                                    </div>    
                                                    
                                                    @endforeach
                                                    <div id="custom-steps-{{ $task->id }}"></div>

                                                </div>
                                            </div>  --}}

                                                

                                            <div class="col-md-12">
                                                <div class="form-group" style="position: relative;">
                                                    <label for="note">Notes</label>
                                                    {{ Form::text("tasks[$task->id][notes]",
                                                                    $task->notes,
                                                                    ['class' => "form-control reset-$task->id",
                                                                     'placeholder' => 'Enter notes'
                                                                    ]    
                                                                ) 
                                                    }}
                                                </div>
                                            </div>

                                            {{-- {{ dump($proposal->proposalEntries[0]->media) }} --}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        @endforeach
                        
                        </div> <!-- end of accordion -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-info">
                                  <div class="panel-heading">Approval</div>
                                  <div class="panel-body">
                                    <div class="checkbox">
                                        <label>
                                        @if($proposal->approved == 'Yes')
                                            {!! Form::checkbox('approved', 1, true, 
                                            ['id' => 'approved', 'checked']) !!} 
                                        @else
                                            {!! Form::checkbox('approved', 1, false, 
                                            ['id' => 'approved']) !!}    
                                        @endif
                                        Approve
                                        <span class="form-error" style="display: block;"></span>
                                        </label>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" id="add-button">Update</button>
                        <a class="btn btn-danger" 
                            id="add-button" 
                            href="{{ Request::url() }}"
                            style="margin-left: 5px;" 
                            >Reset</a>
                        
                    </div>
                    
                </div> <!-- Panel body -->

                {!! Form::close() !!}

            </div>
            {{-- {{ dump($proposal->proposalEntries) }} --}}
        </div>
    </div>
</div>

{{-- Modals --}}

<!-- Parts Modal -->
<div class="modal fade" id="new-task-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    {!! Form::open(['route' => 'task.add', 'id' => 'new-task-form']) !!}
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Task</h4>
      </div>
      <div class="modal-body">

        <div class="row">
            <div class="col-md-6">
                {!! Form::hidden('proposal_id', $proposal->id) !!}
                <div class="form-group">
                    <label for="location">Location</label>
                    {!! Form::select("task_location", 
                        [
                            null => 'Select location',
                        ] + $locations, 
                        null, 
                        ['id' => 'task_location', 
                        'class' => 'form-control',
                        'style' => 'width: 100%']) !!}
                    
                    <span class="form-error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="room">Room</label>
                    {!! Form::select("task_room", 
                        [
                            null => 'Select room',
                        ] + \App\Room::orderBy('area')->pluck('area', 'id')->toArray(), 
                        null, 
                        ['id' => 'task_room', 
                        'class' => 'form-control',
                        'style' => 'width: 100%']) !!}
                    
                    <span class="form-error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type">Type</label>
                    {!! Form::select("task_type", 
                        [
                            null => 'Select type',
                        ] + \App\Type::orderBy('type')->pluck('type', 'id')->toArray(), 
                        null, 
                        ['id' => "task_type", 
                        'class' => 'form-control',
                        'style' => 'width: 100%']) !!}
                    
                    <span class="form-error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sub_type">Sub Type</label>
                    {!! Form::select("task_sub_type", 
                        [
                            null => 'Select Sub Type',
                        ] , 
                        null,
                        ['id' => "task_sub_type", 
                        'class' => "form-control",
                        'style' => 'width: 100%',
                        'disabled',
                        ]) !!}
                    
                    <span class="form-error"></span>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <label for="work_item">Work Item</label>
                    {!! Form::select("task_work_item", 
                        [
                            null => 'Select Work Item',
                        ],
                        null, 
                        ['id' => "task_work_item", 
                        'class' => "form-control",
                        'style' => 'width: 100%',
                        'disabled',
                        'placeholder' => 'Select Work Item']) !!}
                    
                    <span class="form-error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="list-price">List Price</label>
                {!! Form::text("task_list_price",
                    null,
                    [
                        'class' => "form-control",
                        'placeholder' => 'Enter list price',
                        'id' => 'task_list_price'
                    ]   
                ) !!}
                <span class="form-error"></span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                <label for="extended-price">Extended Price</label>
                {!! Form::text("task_extended_price",
                    null,
                    [
                        'class' => "form-control",
                        'placeholder' => 'Enter extended price',
                        'id' => 'task_extended_price'
                    ]   
                ) !!}
                <span class="form-error"></span>
                </div>
            </div>    

            <div class="col-md-12">
                <div class="form-group" style="position: relative;">
                    <label for="note">Notes</label>
                    {{ Form::text("task_note",
                            null,
                            [
                                'class' => "form-control",
                                'placeholder' => 'Enter notes',
                                'id' => 'task_note'
                            ]    
                        ) 
                    }}
                    <span class="form-error"></span>
                </div>
            </div>
        </div>
        <div class="text-info">
            *More task details can be added in the edit section.
        </div>  
      </div>
      
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm" id="add-task-button" style="width: 70px;">Add</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
</div>
<!-- end of Parts Modal -->

@endsection

@push('script')
{!! Html::script('public/js/jquery.noty.packaged.js') !!}
{!! Html::script('public/js/jquery.sortable.js') !!}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
</script>
<script>
    
    /*$('form').submit(function(e){
        e.preventDefault();

        $('span.form-error').hide();
        $('#add-button').text('Updating...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: 'POST',
            dataType: 'json',
            success: function(res)
            {
                generate('success', '<div class="activity-item">\
                    <i class="fa fa-check text-success"></i>\
                    <div class="activity">' + res.success + '</div></div>');
                },
            error: function(res)
            {
                var errors = JSON.parse(res.responseText);
                $.each(errors, function(key, value){
                    $('#'+key).siblings('span.form-error').html(value).fadeIn();
                });
            },
            complete: function()
            {
                $('#add-button').text('Update').prop('disabled', false);
            }
        });
    });*/

    function generate(type, text) {

        var n = noty({
            text        : text,
            type        : type,
            layout      : 'topRight',
            theme       : 'relax',
            dismissQueue: true,
            timeout     : 4000,
            closeWith   : ['click', 'timeout'],
            animation   : {
                open  : 'animated fadeInDown',
                close : 'animated fadeOutUp',
                easing: 'swing',
                speed : 500
            },
            buttons: false
        });
    }

    function generateAlert(layout, text) {
        var n = noty({
            text        : text,
            type        : 'alert',
            dismissQueue: true,
            layout      : layout,
            modal       : true,
            theme       : 'relax',
            buttons     : [
                {addClass: 'btn btn-success', text: 'Ok', onClick: function ($noty) {
                    $noty.close();
                    
                    }
                }
            ]
        });
    }

    function generateConfirm(layout, goto) {
        var n = noty({
            text        : 'This task will be deleted. Do you want to continue?',
            type        : 'alert',
            dismissQueue: true,
            layout      : layout,
            modal       : true,
            theme       : 'relax',
            buttons     : [
                {addClass: 'btn btn-primary', text: 'Ok', onClick: function ($noty) {
                    $noty.close();
                    window.location = goto;
                    }
                },
                {addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {
                    $noty.close();
                    }
                }
            ]
        });
    }

    $(function(){
        $('.select-location').select2({
            placeholder: 'Select Location',
        });

        $('.select-room').select2({
            placeholder: 'Select Room',
        });

        $('.select-type').select2({
            placeholder: 'Select Type',
        });

        $('.select-sub-type').select2({
            placeholder: 'Select Sub Type',
        });

        $('.select-work-item').select2({
            placeholder: 'Select Work Item',
        });

        $('.select-parts').select2({
            placeholder: 'Select Parts',
        });

        $('.select-steps').select2({
            placeholder: 'Select step',
        });

        $('#new-task-form #task_location').select2({
            placeholder: 'Select location'
        });

        $('#new-task-form #task_room').select2({
            placeholder: 'Select room'
        });

        $('#new-task-form #task_type').select2({
            placeholder: 'Select type'
        });

        $('#new-task-form #task_sub_type').select2({
            placeholder: 'Select sub type'
        });

        $('#new-task-form #task_work_item').select2({
            placeholder: 'Select work item'
        });

        $('.list-group-sortable').sortable({
            placeholderClass: 'list-group-item'
        });

        // make accordion collapse set to false
        $('#accordion .panel-collapse').collapse({
            toggle: false
        });

        $('#accordion').on('hidden.bs.collapse', toggleChevron);
        $('#accordion').on('shown.bs.collapse', toggleChevron);
    });

    function toggleChevron(e) {
        $(e.target)
            .prev('.panel-heading')
            .find("i.indicator")
            .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
    }

    $(document).on('click', '.remove-extra-parts', function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
    });

    var part_count = 0;

    $('.add-parts').click(function(e) {
        e.preventDefault();
        
        var target = $(this).attr('data-target');
        var task_id = $(this).attr('data-task-id');
        
        if(part_count <= $(this).attr('data-count'))
            part_count = $(this).attr('data-count');

        var element = '\
            <div style="position: relative">\
            <input type="hidden" name="tasks['+ task_id +'][extra_parts]['+ part_count +'][id]" value="0" />\
            <div class="panel panel-default extra-part-panel" style="width: 95%">\
              <div class="panel-body">\
                <div class="row">\
                    <div class="col-md-4">\
                        <input type="text" \
                                name="tasks['+ task_id +'][extra_parts]['+ part_count +'][part]"\
                                class="form-control reset-'+ task_id +'"\
                                placeholder="Enter part name" />\
                    </div>\
                    <div class="col-md-4">\
                        <input type="text" \
                                name="tasks['+ task_id +'][extra_parts]['+ part_count +'][price]"\
                                class="form-control reset-'+ task_id +'"\
                                placeholder="Enter part unit price" />\
                    </div>\
                    <div class="col-md-4">\
                        <input type="text" \
                                name="tasks['+ task_id +'][extra_parts]['+ part_count +'][quantity]"\
                                class="form-control reset-'+ task_id +'"\
                                placeholder="Enter part quantity"/>\
                    </div>\
                    <div class="col-md-12">\
                        <input type="text" \
                                name="tasks['+ task_id +'][extra_parts]['+ part_count +'][part_desc]"\
                                class="form-control reset-'+ task_id +'"\
                                placeholder="Enter part description"  style="margin-top: 5px;"/>\
                    </div>\
                </div>\
              </div>\
            </div>\
            <a href="#remove" class="button-right-add remove-extra-parts" title="Remove">\
                <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>\
            </a>\
            </div>\
        ';

        $(target).append(element);
        part_count++;
        
    });

    var step_count = 0;

    // Add steps to task
    $('.add-steps').click(function(e) {
        e.preventDefault();

        $('#label-steps').show();
        var target = $(this).attr('data-target');
        var step_id = $(target).val();
        var step = $(target).select2('data')[0].text;
        var task_id = $(this).attr('data-task-id');
        
        if(step_count <= $(this).attr('data-count'))
            step_count = $(this).attr('data-count');

        var d = new Date();
        step_count = d.getTime();
        
        if(step_id)
        {
            if(step_id == 'extra')
            {
                
            }
            else
            {
                var step_array = [];

                $('.list-group-'+ task_id +' li.list-group-item').each(function(){
                    var type = $(this).children('div')
                                      .children('.tags').children('input.type').val();
                    
                    if(type == "step")
                    {
                        step_array.push(parseInt($(this).find('.item_step_id').val()));
                    }
                    
                });


                // check if step is already added
                if(step_array.includes(parseInt(step_id)))
                    generateAlert('center', 'This step already added!');
                else
                {
                    $('.list-group-' + task_id).append('<li class="list-group-item" id="step-li-'+step_id+'">\
                        <div style="position: relative;">\
                        <div class="tags">\
                            '+step+'\
                            <input type="hidden" class="item_step_id" name="tasks['+task_id+'][steps_new]['+step_count+'][item_step_id]" value="'+step_id+'">\
                            <input type="hidden" class="step" name="tasks['+task_id+'][steps_new]['+step_count+'][step]" value="'+step+'">\
                            <input type="hidden" class="step_order" name="tasks['+task_id+'][steps_new]['+step_count+'][step_order]" value="0">\
                            <input type="hidden"  class="type" name="tasks['+task_id+'][steps_new]['+step_count+'][type]" value="step">\
                            <input type="hidden"  class="type" name="tasks['+task_id+'][steps_new]['+step_count+'][step_desc]" value="">\
                        </div>\
                        <a href="#remove"\
                            class="button-right-add remove-steps"\
                            title="Remove"\
                            data-item-id="'+step_id+'"\
                            data-target="#step-li-'+step_id+'">\
                            <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>\
                        </a>\
                        </div>\
                    </li>');

                    step_count++;
                }

            }

            $(target).val(null).trigger('change');

            $('.list-group-' + task_id).sortable('destroy');
            $('.list-group-' + task_id).sortable({
                placeholderClass: 'list-group-item'
            });

        }

    });

    // remove step on click
    $(document).on('click', '.remove-steps', function(e){
        e.preventDefault();

        $(this).parent('div').parent('li').remove();

    });

    // add extra step to step list
    $('.add-extra-step').click(function(e){
        e.preventDefault();

         $('#label-steps').show();

        var task_id = $(this).attr('data-task-id');
        var new_step = $('#new-step-' + task_id).val();
        var new_step_desc = $('#new-step-desc-' + task_id).val();

        if(step_count == 0)
            step_count = $('.list-group-'+ task_id +' li.list-group-item').length;
        var d = new Date();
        step_count = d.getTime();
        if(new_step != "")
        {
            $('.list-group-' + task_id).append('<li class="list-group-item" id="">\
                <div style="position: relative;">\
                <div class="tags">\
                    *'+new_step+'\
                    <input type="hidden" class="item_step_id" name="tasks['+task_id+'][steps_new]['+step_count+'][item_step_id]" value="0">\
                    <input type="hidden" class="step" name="tasks['+task_id+'][steps_new]['+step_count+'][step]" value="'+new_step+'">\
                    <input type="hidden" class="step_order" name="tasks['+task_id+'][steps_new]['+step_count+'][step_order]" value="0">\
                    <input type="hidden"  class="type" name="tasks['+task_id+'][steps_new]['+step_count+'][type]" value="extra_step">\
                    <input type="hidden"  class="type" name="tasks['+task_id+'][steps_new]['+step_count+'][step_desc]" value="'+new_step_desc+'">\
                </div>\
                <a href="#remove"\
                    class="button-right-add remove-steps"\
                    title="Remove"\
                    data-item-id=""\
                    data-target="">\
                    <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>\
                </a>\
                </div>\
            </li>');

            $('.list-group-' + task_id).sortable('destroy');
            $('.list-group-' + task_id).sortable({
                placeholderClass: 'list-group-item'
            });

            $('#new-step-' + task_id).val('');
            $('#new-step-desc-' + task_id).val('');

            // step_count++;
        }
    });

    var parts = 0;
    var parts_data = "";
    var selected_parts = [];

    $('.add-parts-button').click(function(e) {
        e.preventDefault();
        
        var target = $(this).attr('data-target');
        var task_id = $(this).attr('data-task-id');
        selected_parts = [];

        var parts_data = $('.parts-' + task_id);
        
        $.each(parts_data, function( key, part ) {
            selected_parts.push(part.value);
        });

        if(parts <= $(this).attr('data-count'))
            parts = $(this).attr('data-count');

        var str= "";
        
        $.ajax({
            url: "{{ route('proposal-edit-get-parts') }}",
            data: {item: $('#work_item-' +task_id).val(), selected: selected_parts},
            dataType: 'json',
            type: 'POST',
            success: function(res)
            {
                if(res.res != null)
                {
                    str += '<option value selected>Select Part</option>';

                    $.each(res.res, function(key, value){
                        str += '<option value="'+ value +'">'+ key +'</option>';
                    });

                    if(parts <= $(this).attr('data-count'))
                        parts = $(this).attr('data-count');

                    var element = '\
                        <div style="position: relative">\
                        <div class="panel panel-default extra-part-panel" style="width: 95%">\
                          <div class="panel-body">\
                            <div class="row">\
                                <div class="col-md-4">\
                                    <select \
                                            name="tasks['+ task_id +'][parts]['+ parts +'][part_id]"\
                                            class="dynamic-select-2 form-control select-parts parts-'+ task_id +' reset-'+ task_id +'" style:width: 100%">\
                                            '+ str +'\
                                    </select>\
                                </div>\
                                <div class="col-md-4">\
                                    <input type="text" \
                                            name="tasks['+ task_id +'][parts]['+ parts +'][price]"\
                                            class="form-control part-input reset-'+ task_id +'"\
                                            placeholder="Part unit price" readonly/>\
                                </div>\
                                <div class="col-md-4">\
                                    <input type="text" \
                                            name="tasks['+ task_id +'][parts]['+ parts +'][quantity]"\
                                            class="form-control part-quantity reset-'+ task_id +'"\
                                            placeholder="Part quantity" value="1"/>\
                                </div>\
                            </div>\
                          </div>\
                        </div>\
                        <a href="#remove" class="button-right-add remove-extra-parts" title="Remove">\
                            <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>\
                        </a>\
                        </div>\
                    ';

                    $(target).append(element);
                    parts++;

                    $('.dynamic-select-2').select2({
                        placeholder: 'Select Part',
                    });

                }
            }
        });
        
    });

    $('.select-type').change(function(event) {

        var type = $(this).val();
        var target = $(this).attr('data-target');
        var task_id = $(this).attr('data-task-id');

        $('.extra-part-panel.clear-' + task_id).parent('div').remove();
        $('.hidden-part-' + task_id).remove();
        $('.list-group-' + task_id + ' li').remove();

        var reset_class = $(this).attr('data-target-reset');

        $(reset_class).val('').attr('value', '');
        $(reset_class).val(null).trigger('change');
        $('select'+reset_class).select2('destroy').empty().select2({
            placeholder: 'Choose...',
        });
        $('select'+reset_class).empty();

        // $(target).val(null).trigger('change')
        
        $(target).select2({
            
            placeholder: 'Select Sub Type',
            
            ajax: {
                url : '{{ route('sub-types.list') }}',
                type: 'GET',
                delay: 250,
                data: function (term) {
                    return {
                        q: term,
                        type: type,
                    };
                },
                processResults: function (data) {

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.sub_type,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
        
    });

    $('.select-sub-type').change(function() {

        var task_id = $(this).attr('data-task-id');
        var sub_type = $(this).val();
        var type = $('#type_id-' + task_id).val();

        $('.extra-part-panel.clear-' + task_id).parent('div').remove();
        $('.hidden-part-' + task_id).remove();
        $('.list-group-' + task_id + ' li').remove();

        $('#list-price-'+ task_id).val(null);
        $('#extended-price-'+ task_id).val(null);
        $('#work_item-'+ task_id).empty();
        $('.parts-' + task_id).empty();
        $('.steps-' + task_id).empty();
        $('select.parts-'+task_id).select2('destroy').empty().select2({
                placeholder: 'Choose...',
            });
        $('select.steps-'+task_id).select2('destroy').empty().select2({
                placeholder: 'Choose...',
            });

        if(sub_type)
        {
            $('#work_item-'+ task_id).select2({
                
                placeholder: 'Select Work Item',
                
                ajax: {
                    url : '{{ route('items.list') }}',
                    type: 'GET',
                    delay: 250,
                    data: function (term) {
                        return {
                            q: term,
                            type: type,
                            sub_type: sub_type
                        };
                    },
                    processResults: function (data) {

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.detail,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });
        }

    });

    $('.select-work-item').change(function(){
        var item = $(this).val();
        var task_id = $(this).attr('data-task-id');

        $('.extra-part-panel.clear-' + task_id).parent('div').remove();
        $('.hidden-part-' + task_id).remove();
        $('.list-group-' + task_id + ' li').remove();
        
        $('.parts-'+ task_id).empty();
        $('.parts-'+ task_id).parent('div').siblings('.div-part-price').children('.part-input').val(null);
        $('.steps-'+ task_id).empty();
        $('#main-parts-'+ task_id).html('');
        
        if(item)
        {
            $('.parts-'+ task_id).select2({
                
                placeholder: 'Select Part',
                
                ajax: {
                    url : '{{ route('proposal-edit-get-parts-2') }}',
                    type: 'POST',
                    delay: 250,
                    data: function (term) {
                        return {
                            q: term,
                            item: item,
                        };
                    },
                    processResults: function (data) {

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.part,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });

            $('.steps-'+ task_id).select2({
                
                placeholder: 'Select step',
                
                ajax: {
                    url : '{{ route('proposal-edit-get-steps') }}',
                    type: 'POST',
                    delay: 250,
                    data: function (term) {
                        return {
                            q: term,
                            item: item,
                        };
                    },
                    processResults: function (data) {

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.detail,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });

            $.ajax({
                url: '{{ route('item.price') }}',
                data: {item_id: item},
                type: 'POST',
                dataType: 'json',
                success: function(res)
                {
                    $('#list-price-' + task_id).val(res.price);
                    $('#extended-price-' + task_id).val(null);
                }
            });

        }
    });

    var price = 0;

    $(document).on('change', '.select-parts', function(){
        var part_id = $(this).val();
        price = 0;

        var input = $(this).closest('div.col-md-4')
                           .siblings('div.col-md-4')
                           .children('input.part-input');

        $.ajax({
            url: '{{ route('part.price') }}',
            type: 'POST',
            dataType: 'json',
            data: {part_id: part_id},
        })
        .success(function(data) {
            input.val(data.price);

        });
        
    });

    /**
     * Add new task
     */

    $('.add-task-link').click(function(e) {
        e.preventDefault();
    });

    $('#new-task-form').submit(function(e) {
        e.preventDefault();

        $('#new-task-form span.form-error').hide();
        $('#add-task-button').text('Adding...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: 'POST',
            dataType: 'json',
            success: function(res)
            {
                if(res.status)
                {
                    $('#new-task-form')[0].reset();
                    $('#new-task-form select').val(null).trigger('change');
                    generate('success', '<div class="activity-item">\
                    <i class="fa fa-check text-success"></i>\
                    <div class="activity">' + res.success + '</div></div>');
                    setTimeout(function(){ 
                        location.reload();
                    }, 1000);
                }
            },    
            error: function(res)
            {
                var errors = JSON.parse(res.responseText);
                $.each(errors, function(key, value){
                    $('#'+key).siblings('span.form-error').html(value).fadeIn();
                });
            },
            complete: function()
            {
                $('#add-task-button').text('Add').prop('disabled', false);
            }
        });
    });

    $('#task_type').change(function(event) {

        var type = $(this).val();
        $('#task_sub_type').attr('disabled', true);
        $('#task_work_item').attr('disabled', true);
        $('#task_list_price').val('');
        
        $('#task_sub_type').val('').attr('value', '');
        $('#task_sub_type').val(null).trigger('change');
        $('#task_sub_type').select2('destroy').empty().select2({
            placeholder: 'Select sub type',
        });
        $('#task_work_item').select2('destroy').empty().select2({
            placeholder: 'Select work item',
        });
        $('#task_sub_type').empty();
        $('#task_work_item').empty();

        $('#task_sub_type').select2({
            
            placeholder: 'Select Sub Type',
            
            ajax: {
                url : '{{ route('sub-types.list') }}',
                type: 'GET',
                delay: 250,
                data: function (term) {
                    return {
                        q: term,
                        type: type,
                    };
                },
                processResults: function (data) {

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.sub_type,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });

        $('#task_sub_type').attr('disabled', false);
        
    });

    $('#task_sub_type').change(function() {

        var sub_type = $(this).val();
        var type = $('#task_type').val();
        $('#task_work_item').attr('disabled', true);
        $('#task_work_item').empty();
        $('#task_list_price').val('');

        if(sub_type)
        {
            $('#task_work_item').select2({
                
                placeholder: 'Select work item',
                
                ajax: {
                    url : '{{ route('items.list') }}',
                    type: 'GET',
                    delay: 250,
                    data: function (term) {
                        return {
                            q: term,
                            type: type,
                            sub_type: sub_type
                        };
                    },
                    processResults: function (data) {

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.detail,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });
        }

        $('#task_work_item').attr('disabled', false);

    });

    $('.task-remove-button').click(function(e){
        e.preventDefault();

        generateConfirm('center', $(this).attr('href'));
    });

    $(document).on('change', '#task_work_item', function(){
        
        $('#task_list_price').val('');
        var item_id = $(this).val();
        
        var input = $('#task_list_price');

        $.ajax({
            url: '{{ route('item.price') }}',
            type: 'POST',
            dataType: 'json',
            data: {item_id: item_id},
        })
        .success(function(data) {
            input.val(data.price);

        });
        
    });

</script>
@endpush
