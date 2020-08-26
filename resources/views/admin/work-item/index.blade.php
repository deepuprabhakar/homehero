@extends('admin.app')

@section('title')
    Home Hero - List of Work Items
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
                    List of Work Items
                    <div class="pull-right">
                        <a href={{ route('admin.work-items.create') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                <div class="">
                <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" id="work-item-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th style="max-width: 200px;">Details</th>
                            <th>Type</th>
                            <th>Sub Type</th>
                            <th>Item ID</th>
                            <th>Price ($)</th>
                            <th>Est Hrs</th>
                            <th class="text-center">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center text-muted" colspan="6">Loading...</td>
                        </tr>
                    </tbody>
                </table>
                </div>
                </div>
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
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
</script>
<script>
    $(function() {
        $('#work-item-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth : false,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                    url: '{!! route('work-items.list') !!}',
                    method: 'POST'
                },
            "order": [],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'detail', name: 'detail', width: '100px' },
                { data: 'type', name: 'type' },
                { data: 'sub_type', name: 'sub_type' },
                { data: 'item_id', name: 'item_id' },
                { data: 'price', name: 'price' },
                { data: 'est_hrs', name: 'est_hrs' },
                { data: 'action', name: 'action' },
                
            ],
            columnDefs: [
                {
                    "targets": [ 0, 6 ],
                    "sortable": false,
                    "searchable": false,
                },
                {
                    "targets": [ 2, 7 ],
                    "hidden": true
                },
                
            ],
            /*details: {
                renderer: function ( api, rowIdx, columns ) {
                    var data = $.map( columns, function ( col, i ) {
                        return col.hidden ?
                            '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+':'+'</td> '+
                                '<td>'+col.data+'</td>'+
                            '</tr>' :
                            '';
                    } ).join('');
 
                    return data ?
                        $('<table/>').append( data ) :
                        false;
                }
            },*/
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                //debugger;
                var index = iDisplayIndexFull + 1;
                $("td:first", nRow).html(index);
                return nRow;
            }
        });
    });

    function generate(layout, form) {
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

    $(document).on('click', '.datatable-action.delete', function(e){
        e.preventDefault();
        var form = $(this).parent('form');
        generate('center', form);
    });

</script>
@endpush
