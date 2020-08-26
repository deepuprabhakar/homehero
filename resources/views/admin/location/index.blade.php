@extends('admin.app')

@section('title')
    Home Hero - List of Locations
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
                    List of Locations
                    <div class="pull-right">
                        <a href={{ route('admin.locations.create') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="location-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Sub Type</th>
                            <th>Location ID</th>
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
        $('#location-table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                    url: '{!! route('locations.list') !!}',
                    method: 'POST'
                },
            "order": [],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'type', name: 'type' },
                { data: 'sub_type', name: 'sub_type' },
                { data: 'loc_id', name: 'loc_id' },
                { data: 'action', name: 'action' }
            ],
            columnDefs: [
                {
                    "targets": [ 0, 4 ],
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
