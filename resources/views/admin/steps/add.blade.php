@extends('admin.app')

@section('title')
    Home Hero - Add Item Steps
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
                    Add Item Steps
                    <div class="pull-right">
                        <a href={{ route('admin.item-steps.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                {!! Form::open(['route' => 'admin.item-steps.store', 'id' => 'work-item-form']) !!}
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
                        {{-- <div class="add-more-div"></div>
                        <div class="col-md-12">
                            <div class="form-group" style="position: relative;">
                                <button class="btn btn-success btn-xs" id="add-more-button" type="button">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add More
                                </button>
                            </div>
                        </div> --}}
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
                $('#work-item-form')[0].reset();
                $('#item_id').val('').trigger('change');
                generate('success', '<div class="activity-item">\
                    <i class="fa fa-check text-success"></i>\
                    <div class="activity">' + res.success + '</div></div>');
                $('.add-more-div').html('');
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
        $('#item_id').select2({
            placeholder: 'Select Work Item',
            // minimumResultsForSearch: Infinity
          });
    });

    $('#add-more-button').click(function(){
        $('.add-more-div').append('<div class="col-md-12">\
                                    <div class="form-group" style="position: relative;">\
                                    <input name="steps[]" class="form-control" placeholder="Enter step"/>\
                                    <div class="close-button"><i class="fa fa-times-circle" aria-hidden="true"></i></div>\
                                    </div>\
                                    </div>');
    });

    $(document).on('click', '.close-button', function(){
        $(this).parent('div').closest('.col-md-12').remove();
    });
</script>
@endpush
