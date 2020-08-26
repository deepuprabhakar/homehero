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
                        <th>Id</th>
                        <th>Name</th>
                        <th>Phone</th>
                        {{-- <th>Address</th> --}}
                        <th>Created At</th>
                        <th class="text-center">Versions</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                </table>
                
                <script id="details-template" type="text/x-handlebars-template">
                    {{-- <div class="label label-info custom-label">@{{ count }} Proposal Entry(s)</div> --}}
                    <div class="row">
                        <div class="col-md-2">
                            <div class="label label-primary custom-label">
                                <span class="badge">@{{ count }}</span> Proposal Entry(s) 
                            </div>
                        </div>
                        <div class="col-md-1">
                            <a href="@{{ view_url }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                View
                            </a>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-info btn-sm">
                                @{{ approved }}
                            </a>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div><b>Address</b>: <address>@{{ address }}</address></div>
                                    <div><b>Job Address</b>: <address style="margin-bottom: 0;">@{{ job_address }}</address></div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-1">
                            <a href="@{{ edit_url }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                Edit
                            </a>
                        </div> --}}

                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">Staff Name: @{{ staff }}</div>
                    </div>
                    <div class="panel panel-info" style="margin-bottom: 0;">
                        <div class="panel-heading">Proposal Entries</div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="entry-@{{id}}">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>List Price ($)</th>
                                        <th>Ext. Price ($)</th>
                                    </tr>
                                </thead>
                            </table>
                        
                        </div>
                    </div>
                    
                </script>
                {{-- end of script template --}}
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
{!! Html::script('public/js/handlebars.js') !!}
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
</script>
<script>
    $(function() {
        
        var template = Handlebars.compile($("#details-template").html());
            var table = $('#proposal-table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                ajax: {
                    url: '{{ route('proposals.list') }}',
                    method: 'POST'
                },
                columns: [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "searchable":      false,
                        "data":           'id',
                        "defaultContent": ''
                    },
                    {data: 'id', name: 'id'},
                    {data: 'firstname', name: 'firstname'},
                    {data: 'phone', name: 'phone'},
                    // {data: 'address', name: 'address'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'versions', name: 'versions'},
                    {data: 'actions', name: 'actions'},
                ],
                order: [],
                columnDefs: [
                        {
                            "targets": [ 0, 5, 6 ],
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

            // Add event listener for opening and closing details
            $('#proposal-table tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var tableId = 'entry-' + row.data().id;

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(template(row.data())).show();
                    initTable(tableId, row.data());
                    tr.addClass('shown');
                    tr.next().find('td').addClass('no-padding bg-gray');
                }
            });

            function initTable(tableId, data) {
                $('#' + tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    lengthChange: false,
                    searching: false,
                    ajax: {
                        url: data.details_url,
                        method: 'POST'
                    },
                    order: [],
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'type', name: 'work_items.type' },
                        { data: 'detail', name: 'detail' },
                        { data: 'list_price', name: 'list_price' },
                        { data: 'extended_price', name: 'extended_price' }
                    ],
                    columnDefs: [
                        {
                            "targets": [ 0 ],
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
                })
            }

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
