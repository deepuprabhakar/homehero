@extends('admin.app')

@section('title')
    {{ env('APP_NAME') }} - Change Password
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
                <div class="panel-heading">Change Password</div>

                <div class="panel-body">
                {!! Form::open(['route' => 'admin.password.save', 
                                'id' => 'password-form', 
                                'autocomplete' => 'off']) !!}
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    {!! Form::password('current_password', 
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Enter current password',
                            'id' => 'current_password'
                        ])
                    !!}
                    <span class="form-error"></span>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    {!! Form::password('password', 
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Enter new password',
                            'id' => 'password'
                        ])
                    !!}
                    <span class="form-error"></span>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    {!! Form::password('password_confirmation', 
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Confirm new password',
                            'id' => 'password_confirmation'
                        ])
                    !!}
                    <span class="form-error"></span>
                </div>

                <button type="submit" class="btn btn-primary password-save">Save</button>

                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
{!! Html::script('public/js/jquery.noty.packaged.js') !!}
<script>
    $('#password-form').submit(function(e){
        e.preventDefault();

        $('span.form-error').hide();
        $('.password-save').text('Saving...').attr('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: 'POST',
            dataType: 'json',
            success: function(res)
            {
                if(res.status)
                {
                    $('#password-form')[0].reset();
                    generate('success', '<div class="activity-item">\
                    <i class="fa fa-check text-success"></i>\
                    <div class="activity">' + res.message + '</div></div>');
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
                $('.password-save').blur();
                $('.password-save').text('Save').attr('disabled', false);
            }
        })
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
</script>
@endpush
