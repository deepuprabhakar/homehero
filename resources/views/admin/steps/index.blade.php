@extends('admin.app')

@section('title')
    Home Hero - List of Item Steps
@endsection

@push('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css">
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
                    List of Item Steps
                    <div class="pull-right">
                        <a href={{ route('admin.item-steps.create') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="item">Select Work Item</label>
                            {!! Form::select('item_id',
                                [null => 'Select Item']+$items,
                                1,
                                ['id' => 'item_id',
                                'class' => 'form-control',
                                'style' => 'width: 100%']) !!}
                            {{ ($errors->has('item') ? $errors->first('item') : '') }}
                            <span class="form-error"></span>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="step-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Step ID</th>
                            <th>Detail</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr>
                            <td class="text-center text-muted" colspan="8">Loading...</td>
                        </tr> --}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
</script>
<script>
    $(function() {
        var item = $('#item_id').val();

        if(item != "")
        {
          $('#step-table').DataTable({
              processing: true,
              serverSide: true,
              lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
              ajax: {
                      url: '{!! route('item-steps.list') !!}',
                      method: 'POST',
                      data: {'item': item}
                  },
              "order": [],
              columns: [
                  { data: 'id', name: 'id' },
                  { data: 'step_id', name: 'step_id' },
                  { data: 'detail', name: 'detail' },
                  { data: 'action', name: 'action' },
              ],
              columnDefs: [
                  {
                      "targets": [ 0, 3 ],
                      "sortable": false,
                      "searchable": false,
                  },
              ],
              "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                  //debugger;
                  var index = iDisplayIndexFull + 1;
                  $("td:first", nRow).html(index);
                  return nRow;
              }
          });
        }
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

    $(function(){
        $('#item_id').select2({
            placeholder: 'Select Work Item',
            // minimumResultsForSearch: Infinity
          });
    });

     $('#item_id').change(function(){

      var item = $(this).val();

      if(item != "")
      {
        //Datatables
          $('#step-table').dataTable().fnDestroy();
          $('#step-table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                    url: '{!! route('item-steps.list') !!}',
                    method: 'POST',
                    data: {'item': item}
                },
            "order": [],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'step_id', name: 'step_id' },
                { data: 'detail', name: 'detail' },
                { data: 'action', name: 'action' },
            ],
            columnDefs: [
                {
                    "targets": [ 0, 3 ],
                    "sortable": false,
                    "searchable": false,
                },
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                //debugger;
                var index = iDisplayIndexFull + 1;
                $("td:first", nRow).html(index);
                return nRow;
            }
        });
      }
    });

</script>
@endpush
