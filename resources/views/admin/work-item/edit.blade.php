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
                    Edit - <span id="edit-title">{{ $workItem->type }}</span> - {{ $workItem->item_id }}
                    <div class="pull-right">
                        <a href={{ route('admin.work-items.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                {!! Form::model($workItem, 
                    ['url' => route('admin.work-items.update', $workItem->id), 
                    'id' => 'work-item-form', 
                    'method' => 'PATCH']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="position: relative;">
                                <label for="type">Choose Item Type</label>
                                {!! Form::select('type_id', 
                                    [
                                        null => 'Select Work Item',
                                    ] + $types, 
                                    null, 
                                    ['id' => 'type_id', 
                                    'class' => 'form-control custom-width',
                                    'style' => 'width: 90%']) !!}
                                    <a href="#" 
                                        class="button-right-add"
                                        data-toggle="modal" 
                                        data-target="#typeModal" 
                                        title="Add Type">
                                        <i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i>
                                    </a>
                                {{ ($errors->has('type_id') ? $errors->first('type_id') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="position: relative;">
                                <label for="sub_type">Choose Item Sub Type</label>
                                {!! Form::select('sub_type_id', 
                                    [
                                        null => 'Select Work Item'
                                        
                                    ]+ $sub_types, 
                                    null, 
                                    ['id' => 'sub_type_id', 
                                    'class' => 'form-control custom-width',
                                    'style' => 'width: 90%']) !!}
                                    <a href="#" 
                                        class="button-right-add"
                                        data-toggle="modal" 
                                        data-target="#subtypeModal" 
                                        title="Add Type" id="sub-type-anchor" onclick="return false">
                                        <i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i>
                                    </a>
                                {{ ($errors->has('sub_type_id') ? $errors->first('sub_type_id') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="detail">Details</label>
                                {!! Form::textarea('detail', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'detail', 
                                                'placeholder' => 'Enter details', 'size' => '5x5']) !!}
                                {{ ($errors->has('detail') ? $errors->first('detail') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="position: relative;">
                                <label for="parts">Choose Parts</label>
                                {!! Form::select('parts[]', 
                                    $parts, 
                                    $selectedParts,
                                    ['id' => 'parts', 
                                    'class' => 'form-control',
                                    'multiple',
                                    'style' => 'width: 95%']) !!}
                                <a href="#" 
                                    class="button-right-add"
                                    data-toggle="modal" 
                                    data-target="#partsModal" 
                                    title="Add Type">
                                    <i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i>
                                </a>
                                {{ ($errors->has('parts') ? $errors->first('parts') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="steps" id="label-steps">
                                    Item Steps
                                </label>
                                <ul class="list-group list-group-sortable list-group-steps">
                                @foreach ($workItem->steps as $key=>$step)
                                    <li class="list-group-item" id="list-group-{{ $key+1 }}" draggable="true">
                                        <div style="position: relative;">
                                            <div class="tags">
                                                {{ $step->detail }}    
                                                <input type="hidden" class="item_step" name="steps[]" value="{{ $step->detail }}">
                                            </div>                 
                                            <a href="#remove" class="button-right-add remove-steps" title="Remove" data-item-id="" data-target="">
                                                <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>
                                            </a>                 
                                        </div>            
                                    </li>
                                @endforeach 
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="position: relative;">
                                <label for="steps">Add Step</label>
                                <a href="#add-step" class="btn btn-primary btn-xs add-parts-button"
                                title="Add Parts" id="add-more-steps" >
                                    <i class="fa fa-plus" aria-hidden="true"></i> 
                                    Add Step
                                </a>
                                
                                <div style="position: relative">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text" 
                                                    name="new_step"
                                                    class="form-control"
                                                    placeholder="Enter step"
                                                    id="new_step"/>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Price (<i class="fa fa-usd" aria-hidden="true"></i>)</label>
                                {!! Form::text('price', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'price', 
                                                'placeholder' => 'Enter price']) !!}
                                {{ ($errors->has('price') ? $errors->first('price') : '') }}
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="est_hrs">Estimated Hours</label>
                                {!! Form::text('est_hrs', 
                                                NULL, 
                                                ['class' => 'form-control', 
                                                'id' => 'est_hrs', 
                                                'placeholder' => 'Enter hours']) !!}
                                {{ ($errors->has('est_hrs') ? $errors->first('est_hrs') : '') }}
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


{{-- Modals --}}

<!-- Parts Modal -->
<div class="modal fade" id="partsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    {!! Form::open(['route' => 'admin.parts.store', 'id' => 'part-form']) !!}
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Part</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="part">Part</label>
                    {!! Form::text('part', 
                                    NULL, 
                                    ['class' => 'form-control', 
                                    'id' => 'part', 
                                    'placeholder' => 'Enter part']) !!}
                    {{ ($errors->has('part') ? $errors->first('part') : '') }}
                    <span class="form-error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price">Price ($)</label>
                    {!! Form::text('price', 
                                    NULL, 
                                    ['class' => 'form-control', 
                                    'id' => 'price', 
                                    'placeholder' => 'Enter price']) !!}
                    {{ ($errors->has('price') ? $errors->first('price') : '') }}
                    <span class="form-error"></span>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="add-button">Add</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
</div>
<!-- end of Parts Modal -->

<!-- Type Modal -->
<div class="modal fade" id="typeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

    {!! Form::open(['route' => 'admin.types.store', 'id' => 'type-form']) !!}
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Type</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="type">Type</label>
                    {!! Form::text('type', 
                                    NULL, 
                                    ['class' => 'form-control', 
                                    'id' => 'type', 
                                    'placeholder' => 'Enter type']) !!}
                    {{ ($errors->has('type') ? $errors->first('type') : '') }}
                    <span class="form-error"></span>
                </div>
            </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" id="add-button">Add</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    {!! Form::close() !!}

    </div>
  </div>
</div>
<!-- end of Type Modal -->

<!-- Sub Type Modal -->
<div class="modal fade" id="subtypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

    {!! Form::open(['route' => 'admin.sub-types.store', 'id' => 'sub-type-form']) !!}
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Sub Type</h4>
      </div>
      <div class="modal-body">
        <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type_id">Choose Item Type</label>
                        {!! Form::select('type_id', 
                            [
                                null => 'Select Work Item',
                            ] + $types, 
                            null, 
                            ['id' => 'type_id', 
                            'class' => 'form-control custom-width',
                            'style' => 'width: 100%']) !!}
                        {{ ($errors->has('type_id') ? $errors->first('type_id') : '') }}
                        <span class="form-error"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sub_type">Sub Type</label>
                        {!! Form::text('sub_type', 
                                        NULL, 
                                        ['class' => 'form-control', 
                                        'id' => 'sub_type', 
                                        'placeholder' => 'Enter type']) !!}
                        {{ ($errors->has('type') ? $errors->first('type') : '') }}
                        <span class="form-error"></span>
                    </div>
                </div>
            </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" id="add-button">Add</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    {!! Form::close() !!}

    </div>
  </div>
</div>
<!-- end of Sub Type Modal -->

{{-- end of Models --}}


@endsection

@push('script')
{!! Html::script('public/js/jquery.noty.packaged.js') !!}
{!! Html::script('public/js/jquery.sortable.js') !!}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    
    // Submit work item form
    $('form#work-item-form').submit(function(e){
        e.preventDefault();

        $('form#work-item-form span.form-error').hide();
        $('form#work-item-form #add-button').text('Saving...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: 'POST',
            dataType: 'json',
            success: function(res)
            {
                // $('#work-item-form')[0].reset();
                // $('#parts,#type_id,#sub_type_id').val('').trigger('change');
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
                $('form#work-item-form #add-button').text('Update').prop('disabled', false);
            }
        });
    });

    // Submit part form
    $('form#part-form').submit(function(e){
        e.preventDefault();

        $('form#part-form span.form-error').hide();
        $('form#part-form #add-button').text('Adding...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: 'POST',
            dataType: 'json',
            success: function(res)
            {
                $('#part-form')[0].reset();

                var parts = [{ id: res.part.id, text: res.part.part }];
                
                generate('success', '<div class="activity-item">\
                    <i class="fa fa-check text-success"></i>\
                    <div class="activity">' + res.success + '</div></div>');
                console.log('parts');
                $('#parts').select2({
                    
                    placeholder: 'Select Parts',
                    allowClear: true,
                    data: parts                    
                });

            },
            error: function(res)
            {
                var errors = JSON.parse(res.responseText);
                $.each(errors, function(key, value){
                    $('form#part-form #'+key).siblings('span.form-error').html(value).fadeIn();
                });
            },
            complete: function()
            {
                $('form#part-form #add-button').text('Add').prop('disabled', false);
            }
        });
    });

    // Submit Type form
    $('form#type-form').submit(function(e){
        e.preventDefault();

        $('form#type-form span.form-error').hide();
        $('form#type-form #add-button').text('Adding...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: 'POST',
            dataType: 'json',
            success: function(res)
            {
                $('#type-form')[0].reset();

                var types = [{ id: res.type.id, text: res.type.type }];

                generate('success', '<div class="activity-item">\
                    <i class="fa fa-check text-success"></i>\
                    <div class="activity">' + res.success + '</div></div>');
                console.log(types);
                $('form#work-item-form #type_id').select2({
                    
                    placeholder: 'Select Type',
                    allowClear: true,
                    data: types                    
                });

                $('form#sub-type-form #type_id').select2({
                    
                    placeholder: 'Select Type',
                    allowClear: true,
                    data: types                    
                });
            },
            error: function(res)
            {
                var errors = JSON.parse(res.responseText);
                $.each(errors, function(key, value){
                    $('form#type-form #'+key).siblings('span.form-error').html(value).fadeIn();
                });
            },
            complete: function()
            {
                $('form#type-form #add-button').text('Add').prop('disabled', false);
            }
        });
    });

    // Submit sub type form
    $('form#sub-type-form').submit(function(e){
        e.preventDefault();

        $('form#sub-type-form span.form-error').hide();
        $('form#sub-type-form #add-button').text('Adding...').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: 'POST',
            dataType: 'json',
            success: function(res)
            {
                $('form#sub-type-form')[0].reset();

                var types = [{ id: res.sub_type.id, text: res.sub_type.sub_type }];

                $('form#sub-type-form #type_id').val(null).trigger('change');

                // $('form#work-item-form #type_id').val(null).trigger('change');
            
                generate('success', '<div class="activity-item">\
                    <i class="fa fa-check text-success"></i>\
                    <div class="activity">' + res.success + '</div></div>');

                /*$('form#work-item-form #sub_type_id').select2({
                    
                    placeholder: 'Select Type',
                    allowClear: true,
                    data: types                    
                });*/

            },
            error: function(res)
            {
                var errors = JSON.parse(res.responseText);
                $.each(errors, function(key, value){
                    $('form#sub-type-form #'+key).siblings('span.form-error').html(value).fadeIn();
                });
            },
            complete: function()
            {
                $('form#sub-type-form #add-button').text('Add').prop('disabled', false);
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
        $('#parts').select2({
            placeholder: 'Select Parts',
            allowClear: true
          });

        $('#type_id').select2({
            placeholder: 'Select Type',
            allowClear: true
          });

        $('#sub-type-form #type_id').select2({
            placeholder: 'Select Type',
            allowClear: true
          });

        $('#sub_type_id').select2({
            
            placeholder: 'Select Sub Type',
            allowClear: true,

            ajax: {
                url : '{{ route('sub-types.list') }}',
                type: 'GET',
                delay: 250,
                data: function (term) {
                    return {
                        q: term,
                        type: $('#type_id').val(),
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

        $('.list-group-sortable').sortable({
            placeholderClass: 'list-group-item'
        });

        $.fn.modal.Constructor.prototype.enforceFocus = function () {};

        // $("#sub_type_id").prop("disabled", true);
    });

    $('#type_id').change(function(event) {

        var type = $(this).val();

        if(type == "")
        {
            $("#sub_type_id").val(null).trigger('change').prop("disabled", true);
        }
        else
        {   
            $("#sub_type_id").val(null).trigger('change').prop("disabled", false);
        }

        $('#sub_type_id').select2({
            
            placeholder: 'Select Sub Type',
            allowClear: true,

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

    /*$('#add-more-steps').click(function(e) {
        e.preventDefault();

        var element = '<div style="position: relative">\
                <div class="row">\
                    <div class="col-md-12">\
                        <input type="text" \
                                name="steps[]"\
                                class="form-control"\
                                placeholder="Enter step" \
                                style="width: 95%"/>\
                    </div>\
                </div>\
            <a href="#remove" class="button-right-add remove-extra-steps" title="Remove">\
                <i class="fa fa-minus-circle fa-3x" aria-hidden="true"></i>\
            </a>\
            </div>\
        ';

        $('#more-steps').append(element);
    });*/

    var step_count = 1;
    $('#add-more-steps').click(function(e) {
        e.preventDefault();
        var step = $('#new_step').val();

        if(step != "")
        {
            $('.list-group-steps').append('<li class="list-group-item" id="list-group-'+ step_count +'">\
                <div style="position: relative;">\
                 <div class="tags">\
                    '+ step +'\
                    <input type="hidden" class="item_step" name="steps[]" value="'+ step +'">\
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

            step_count++;

            $('.list-group-steps').sortable({
                placeholderClass: 'list-group-item'
            });
            $('#new_step').val('');
        }
        
    });

    // remove step on click
    $(document).on('click', '.remove-steps', function(e){
        e.preventDefault();
        $(this).parent('div').parent('li').remove();
    });

    $(document).on('click', '.remove-extra-steps', function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
    });

</script>
@endpush
