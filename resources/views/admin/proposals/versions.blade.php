@extends('admin.app')

@section('title')
    Home Hero - List of Proposal Versions
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
                    List of Proposal Versions for 
                    <a href="{{ route('admin.proposals.show', $proposal) }}">
                        <b>#{{ $proposal->id }}</b>
                    </a>

                    <div class="pull-right">
                        <a href={{ route('admin.proposals.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>

                    <div class="pull-right">
                        <a href={{ route('admin.proposals.edit', $proposal) }} class="btn btn-info admin-add-button">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit 
                        
                        </a>
                    </div>

                    <div class="pull-right">
                        <a href={{ route('admin.proposals.show', $proposal) }} class="btn btn-success admin-add-button">
                        <i class="fa fa-eye" aria-hidden="true"></i> View 
                        
                        </a>
                    </div>
                    
                </div>

                <div class="panel-body">
                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="versions-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Proposal</th>
                            <th>Created</th>
                            <th class="text-center">Sent</th>
                            <th class="text-center">Download</th>
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
        
        var table = $('#versions-table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                    url: '{!! route('proposals.versions.list', $proposal) !!}',
                    method: 'GET'
                },
            "order": [],
            columns: [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": '',
                    "width": '50px'
                },
                { data: 'proposal_id', name: 'proposal_id', 'width': '80px' },
                { data: 'created_at', name: 'created_at', 'width': '150px' },
                { data: 'approved', name: 'approved', 'class': 'text-center' },
                { data: 'file', name: 'file' }
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

        $('body').tooltip({
            selector: '[data-toggle=tooltip]'
        });

        // Add event listener for opening and closing details
        $('#versions-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
     
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
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

    /* Formatting function for row details - modify as you need */
    function format ( d ) {
        // `d` is the original data object for the row
        return '<div class="well well-sm" style="margin-bottom: 0"><table>'+
            '<tr>'+
                '<th>Remarks</th>'+
            '</tr>'+
            '<tr>'+
                '<td class="text-muted">'+d.remarks+'</td>'+
            '</tr>'+
        '</table></div>';
    }

    $(document).on('click', '.datatable-action.delete', function(e){
        e.preventDefault();
        var form = $(this).parent('form');
        generate('center', form);
    });

    $(document).on('click', '.send-proposal', function(e) {
        e.preventDefault();

        var version_id = $(this).attr('id');

        $('#'+ version_id).html('Sending...');

        $.ajax({
            url: '{{ route('proposals.send') }}',
            type: 'POST',
            data: { version: version_id },
            dataType: 'json',
            success: function(res)
            {
                if(res.status == true)
                    $('#'+ version_id).html('<i class="fa fa-check"></i> Sent');
            }
        })
    });

</script>
@endpush
