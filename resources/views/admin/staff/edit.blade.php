@extends('admin.app')

@section('title')
    Edit Staff
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
                    Edit Staff
                    <div class="pull-right">
                        <a href={{ route('admin.staff.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                {!! Form::model($staff, 
                    ['url' => route('admin.staff.update', $staff->id), 
                    'id' => 'admin-form', 
                    'method' => 'PATCH']) !!}
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
                    </div>
                    <button class="btn btn-primary" id="add-button">Update</button>
                    {{-- <button class="btn btn-success" id="resend-button" style="margin-left: 5px;" type="button">
                    Resend Password</button> --}}
                    {!! Form::hidden('staff_id', $staff->id, ['id' => 'current-staff']) !!}
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
{!! Html::script('public/vendor/jquery-mask-plugin/dist/jquery.mask.js') !!}
{!! Html::script('public/js/jquery.noty.packaged.js') !!}
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
                console.log(res);
                // $('#admin-form')[0].reset();
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

    $('#resend-button').click(function(){
        var staff = $('#current-staff').val();

        /*$.ajax({
            
        });*/
    });
</script>
@endpush
