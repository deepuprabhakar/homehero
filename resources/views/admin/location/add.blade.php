@extends('admin.app')

@section('title')
    Home Hero - Add Location
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
                    Add Location
                    <div class="pull-right">
                        <a href={{ route('admin.locations.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                {!! Form::open(['route' => 'admin.locations.store', 'id' => 'location-form']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Choose Location Type</label>
                                {!! Form::select('type', 
                                    [
                                        null => 'Select Work Item',
                                        'Outside' => 'Outside',
                                        'Inside' => 'Inside',
                                        'Other' => 'Other'
                                    ], 
                                    null, 
                                    ['id' => 'type', 
                                    'class' => 'form-control',
                                    'style' => 'width: 100%']) !!}
                                {{ ($errors->has('type') ? $errors->first('type') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sub_type">Enter Location Name (Sub Type)</label>
                                {!! Form::text('sub_type', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'sub_type', 
                                                'placeholder' => 'Enter location name']) !!}
                                {{ ($errors->has('sub_type') ? $errors->first('sub_type') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="add-button">Add</button>
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
        $('#add-button').text('Adding...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: 'POST',
            dataType: 'json',
            success: function(res)
            {
                $('#location-form')[0].reset();
                $('#type').val('').trigger('change');
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
                $('#add-button').text('Add').prop('disabled', false);
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
        $('#type').select2({
            placeholder: 'Select Location Type',
            // minimumResultsForSearch: Infinity
          });
    });
</script>
@endpush
