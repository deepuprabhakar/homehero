@extends('admin.app')

@section('title')
    Edit Client
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
                    Edit Client
                    <div class="pull-right">
                        <a href={{ route('admin.clients.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                {!! Form::model($client, 
                    ['url' => route('admin.clients.update', $client->id), 
                    'id' => 'client-form', 
                    'method' => 'PATCH']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                {!! Form::text('first_name', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'first_name', 
                                                'placeholder' => 'Enter First Name']) !!}
                                {{ ($errors->has('first_name') ? $errors->first('first_name') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                {!! Form::text('last_name', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'last_name', 
                                                'placeholder' => 'Enter Last Name']) !!}
                                {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="home_phone">Home Phone</label>
                                {!! Form::text('home_phone', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'home_phone', 
                                                'placeholder' => 'Enter Home Phone']) !!}
                                {{ ($errors->has('home_phone') ? $errors->first('home_phone') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobile_phone">Mobile Phone</label>
                                {!! Form::text('mobile_phone', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'mobile_phone', 
                                                'placeholder' => 'Enter Mobile Phone']) !!}
                                {{ ($errors->has('mobile_phone') ? $errors->first('mobile_phone') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_address">Address Line 1</label>
                                {!! Form::text('first_address', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'first_address', 
                                                'placeholder' => 'Enter Address']) !!}
                                {{ ($errors->has('first_address') ? $errors->first('first_address') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="office_phone">Office Phone</label>
                                {!! Form::text('office_phone', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'office_phone', 
                                                'placeholder' => 'Enter Office Phone']) !!}
                                {{ ($errors->has('office_phone') ? $errors->first('office_phone') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="second_address">Address Line 2</label>
                                {!! Form::text('second_address', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'second_address', 
                                                'placeholder' => 'Enter Address']) !!}
                                {{ ($errors->has('second_address') ? $errors->first('second_address') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">City</label>
                                {!! Form::text('city', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'city', 
                                                'placeholder' => 'Enter city']) !!}
                                {{ ($errors->has('city') ? $errors->first('city') : '') }}
                                <span class="form-error"></span>
                            </div>  
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state">State</label>
                                {!! Form::select('state', 
                                        [
                                            'Pennsylvania' => 'Pennsylvania',
                                            'New Jersey' => 'New Jersey',
                                            'Delaware' => 'Delaware',
                                        ],
                                        null,
                                        ['id' => 'state', 
                                        'class' => 'form-control',
                                        'style' => 'width: 100%']
                                ) !!}
                                
                                {{ ($errors->has('state') ? $errors->first('state') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                {!! Form::text('zip', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'zip', 
                                                'placeholder' => 'Enter zip']) !!}
                                {{ ($errors->has('zip') ? $errors->first('zip') : '') }}
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
{!! Html::script('public/vendor/jquery-mask-plugin/dist/jquery.mask.js') !!}
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
        $('#state').select2({
            placeholder: 'Select state',
        });

        // $('#home_phone, #mobile_phone, #office_phone').mask('(000) 000-0000');
        // $('#zip').mask('00000');
    });
</script>
@endpush
