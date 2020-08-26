@extends('admin.app')

@section('title')
    Home Hero - List of Proposals
@endsection

@push('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css">
    {!! Html::style('public/css/animate.css') !!}
@endpush

@section('content')
<div class="container">
    <div class="row">
        @include('admin.partials.sidebar')
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    List of Proposals
                    {{-- <div class="pull-right">
                        <a href={{ route('admin.proposals.create') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </a>
                    </div> --}}
                </div>

                <div class="panel-body">
                
                <table id="proposal-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="text-right">No.</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th class="text-center">Approved</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
                
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>
{!! Html::script('public/js/jquery.noty.packaged.js') !!}
{{-- {!! Html::script('public/js/handlebars.js') !!} --}}
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
</script>
<script>
    $(function() {
        
        // var template = Handlebars.compile($("#details-template").html());
            var table = $('#proposal-table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                ajax: {
                    url: '{{ route('proposals.list') }}',
                    method: 'POST'
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'firstname', name: 'firstname'},
                    {data: 'phone', name: 'phone'},
                    {data: 'address', name: 'address'},
                    {data: 'approve_button', name: 'approve_button'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'},
                ],
                order: [],
                columnDefs: [
                        {
                            "targets": [ 0, 4, 6 ],
                            "sortable": false,
                            "searchable": false,
                        }
                    ],
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        //debugger;
                        var index = iDisplayIndexFull + 1;
                        $("td:first", nRow).html(index);
                        return nRow;
                    }
            });

    });

    
    $(document).on('click', '.datatable-action.delete', function(e){
        e.preventDefault();
        var form = $(this).parent('form');
        generate('center', form);
    });

    $(document).on('click', '.click-to-approve', function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        console.log(form);
        generateAlert('center', form);
    });

    $(document).on('submit', '.approval-form', function(e) {
        e.preventDefault();

        var form = $(this);

        $(this).children('div').children('button').html('<i class="fa fa-spinner fa-spin fa-fw"></i> Saving...').prop('disabled', true);

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
                form.children('div').children('button').html('<i class="fa fa-check" aria-hidden="true"></i> Approved').prop('disabled', true);
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

    function generateAlert(layout, form) {
        var n = noty({
            text        : 'Do you want to continue?',
            type        : 'alert',
            dismissQueue: true,
            layout      : layout,
            modal       : true,
            theme       : 'relax',
            buttons     : [
                {addClass: 'btn btn-primary', text: 'Ok', onClick: function ($noty) {
                    $noty.close();
                    form.submit();
                    }
                },
                {addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {
                    $noty.close();
                    }
                }
            ]
        });
    }

</script>
@endpush
