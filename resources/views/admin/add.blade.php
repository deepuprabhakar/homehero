@extends('admin.app')

@section('title')
    {{ env('APP_NAME') }} - Add more admins
@endsection

@push('styles')
{!! Html::style('public/css/animate.css') !!}
@endpush

@section('content')
<div class="container">
    <div class="row">
        @include('admin.partials.sidebar')
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add Admin
                    <div class="pull-right">
                        <a href={{ route('admin.admins.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                {!! Form::open(['route' => 'admin.admins.store', 'id' => 'admin-form']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstname">Firstname</label>
                            {!! Form::text('firstname', 
                                            NULL, 
                                            ['class' => 'form-control', 
                                            'id' => 'firstname', 
                                            'placeholder' => 'Enter firstname']) !!}
                            {{ ($errors->has('firstname') ? $errors->first('firstname') : '') }}
                            <span class="form-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lastname">Lastname</label>
                            {!! Form::text('lastname', 
                                            NULL, 
                                            ['class' => 'form-control', 
                                            'id' => 'lastname', 
                                            'placeholder' => 'Enter lastname']) !!}
                            {{ ($errors->has('lastname') ? $errors->first('lastname') : '') }}
                            <span class="form-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            {!! Form::text('email', 
                                            NULL, 
                                            ['class' => 'form-control', 
                                            'id' => 'email', 
                                            'placeholder' => 'Enter email']) !!}
                            {{ ($errors->has('email') ? $errors->first('email') : '') }}
                            <span class="form-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            {!! Form::text('phone', 
                                            NULL, 
                                            ['class' => 'form-control', 
                                            'id' => 'phone', 
                                            'placeholder' => 'Enter phone']) !!}
                            {{ ($errors->has('phone') ? $errors->first('phone') : '') }}
                            <span class="form-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="admin">Status</label>
                            <div class="checkbox">
                                <label>
                                {!! Form::checkbox('admin', 1, false, ['id' => 'admin']) !!} Active
                                <span class="form-error" style="display: block;"></span>
                                </label>

                            </div>
                        </div>
                    </div>
                </div>
                    <button class="btn btn-primary custom-button" id="add-button">Add</button>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
{!! Html::script('public/js/jquery.noty.packaged.js') !!}
{!! Html::script('public/vendor/jquery-mask-plugin/dist/jquery.mask.js') !!}
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
                $('#admin-form')[0].reset();
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
        // $('#phone').mask('(000) 000-0000');
    });
</script>
@endpush
