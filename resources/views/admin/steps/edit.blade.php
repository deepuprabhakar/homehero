@extends('admin.app')

@section('title')
    Edit Work Item
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
                    Edit - {{ $step->step_id }}
                    <div class="pull-right">
                        <a href={{ route('admin.item-steps.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                {!! Form::model($step, 
                    ['url' => route('admin.item-steps.update', $step->id), 
                    'id' => 'step-form', 
                    'method' => 'PATCH']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="item">Select Work Item</label>
                                {!! Form::select('item_id', 
                                    [null => 'Select Work Item']+$items, 
                                    null, 
                                    ['id' => 'item_id', 
                                    'class' => 'form-control',
                                    'style' => 'width: 100%']) !!}
                                {{ ($errors->has('item') ? $errors->first('item') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="steps">Step</label>
                                {!! Form::text('detail', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'detail', 
                                                'placeholder' => 'Enter Step']) !!}
                                {{ ($errors->has('steps') ? $errors->first('steps') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="add-button">Update</button>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
{!! Html::script('public/js/jquery.noty.packaged.js') !!}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    
    $('form').submit(function(e){
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
                $('#edit-title').text(res.edit_title);
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
    });

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

    $(function(){
        $('#item_id').select2({
            placeholder: 'Select Parts',
            allowClear: true
          });
    });
</script>
@endpush
