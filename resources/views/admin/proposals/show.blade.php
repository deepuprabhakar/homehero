@extends('admin.app')

@section('title')
    Home Hero - Proposal
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
                    Proposal Details - 
                    <a href="{{ route('admin.proposals.show', $proposal->id) }}">#{{ $proposal->id }}</a>

                    <div class="pull-right">
                        <a href={{ route('admin.proposals.index') }} class="btn btn-primary admin-add-button">
                        <i class="fa fa-list" aria-hidden="true"></i> List
                        </a>
                    </div>
                    <div class="pull-right">
                        <a href={{ route('proposals.versions', $proposal) }} class="btn btn-info admin-add-button">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Versions 
                        ({{ $proposal->versions->count() }})
                        </a>
                    </div>

                    <div class="pull-right">
                        <a href={{ route('admin.proposals.edit', $proposal) }} class="btn btn-success admin-add-button">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit 
                        
                        </a>
                    </div>

                </div>

                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="panel panel-primary proposal-panel">
                                <div class="panel-heading">Client Details</div>
                                <div class="panel-body">

                                {{-- Client Details Table --}}
                                @if(!is_null($proposal->client))
                                <div class="table-responsive">
                                    <table class="table table-condensed proposal">
                                        <tbody>
                                            <tr>
                                                <td>Name:</td>
                                                <td>{{ $proposal->client->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td>{{ $proposal->client->email }}</td>
                                            </tr>
                                            <tr>
                                                <td>Home Phone:</td>
                                                <td>{{ $proposal->client->home_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>Mobile Phone:</td>
                                                <td>{{ $proposal->client->mobile_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>Office Phone:</td>
                                                <td>{{ $proposal->client->office_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>Address:</td>
                                                <td>
                                                    {{ $proposal->client->first_address }},
                                                    {{ $proposal->client->second_address }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>City:</td>
                                                <td>{{ $proposal->client->city }}</td>
                                            </tr>
                                            <tr>
                                                <td>State:</td>
                                                <td>{{ $proposal->client->state }}</td>
                                            </tr>
                                            <tr>
                                                <td>Zip:</td>
                                                <td>{{ $proposal->client->zip }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                @endif
                                {{-- edn of Client Details Table --}}

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="panel panel-primary proposal-panel">
                                <div class="panel-heading">Staff Details</div>
                                <div class="panel-body">

                                {{-- Staff Details Table --}}
                                @if($proposal->staff->count() > 0)
                                    <div class="row">
                                    @foreach ($proposal->staff as $staff)
                                        <div class="col-md-6">
                                            <div class="panel panel-default">
                                                <div class="panel-body proposal-staff">
                                                    <div>
                                                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                                                        {{ $staff->name }}
                                                    </div>
                                                    <div>
                                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                        {{ $staff->email }}
                                                    </div>
                                                    <div>
                                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                                        {{ $staff->phone }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-muted">Details not available!</div>
                                        </div>  
                                    </div>
                                @endif
                                {{-- edn of Staff Details Table --}}

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Proposal Details</div>
                                <div class="panel-body">

                                {{-- Proposal Details Table --}}
                                <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Client Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Name:</td>
                                            <td>{{ $proposal->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone:</td>
                                            <td>{{ $proposal->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address:</td>
                                            <td>{{ $address }}</td>
                                        </tr>
                                        <tr>
                                            <td>Job Address:</td>
                                            <td>{{ $job_address }}</td>
                                        </tr>
                                        <tr>
                                            <td>Date:</td>
                                            <td>{{ $proposal->created_at  }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                                </div>

                                @if($proposal->proposalEntries->count() > 0)
                                <div class="table-responsive">
                                <table class="table table-condensed table-bordered proposal">
                                    <thead>
                                        <tr>
                                            <th colspan="7">Proposal Entries</th>
                                        </tr>
                                        <tr>
                                            <th>No.</th>
                                            <th>Type</th>
                                            <th>Details</th>
                                            <th class="text-right">Quantity</th>
                                            <th class="text-right">List Price ($)</th>
                                            <th class="text-right">Ext. Price ($)</th>
                                            <th class="text-right">Final Price ($)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $parts_sum = 0;
                                        $extra_parts_sum = 0;
                                        $original_parts_sum = 0;
                                        $item_sum = 0;
                                        $pivot_sum = 0;
                                    ?>
                                    @foreach ($proposal->proposalEntries as $key => $entry)

                                        <?php
                                        /**
                                         * Calcutate work item sum
                                         */
                                        if($entry->extended_price != 0)
                                            $item_sum += $entry->extended_price;
                                        else
                                            $item_sum += $entry->list_price;
                                        ?>

                                        {{-- Work items --}}
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>
                                                {{ $entry->workItem->type }}
                                            </td>
                                            <td>
                                                <div>{{ $entry->workItem->detail }}</div>
                                            </td>
                                            <td class="text-right">
                                                {{ $entry->quantity }}
                                            </td>
                                            <td class="text-right">
                                                {{ $entry->list_price }}
                                            </td>
                                            <td class="text-right">
                                            @if($entry->extended_price != 0 && ($entry->extended_price != $entry->list_price))
                                                <div class="highlight">{{ $entry->extended_price }}</div>
                                            @else
                                                N/A
                                            @endif
                                            </td>
                                            <td class="text-right">
                                                @if($entry->extended_price != 0)
                                                    {{ $entry->extended_price }}
                                                @else
                                                    {{ $entry->list_price }}
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- parts --}}
                                        @if($entry->parts->count() > 0)

                                            <?php

                                                /**
                                                 * Calculate pivot parts sum
                                                 */
                                                $pivot_sum += $entry->parts->sum('pivot.price');

                                            ?>
                                            
                                            @foreach ($entry->parts as $part)
                                                
                                                <?php

                                                /**
                                                 * calculate original parts sum
                                                 */
                                                $original_parts_sum += $part->price;

                                                /**
                                                 * Calculate parts sum
                                                 */
                                                if($part->pivot->price != 0)
                                                    $parts_sum += ($part->pivot->price * $part->pivot->quantity);
                                                else
                                                    $parts_sum += ($part->price * $part->pivot->quantity);
                                                ?>
                                                
                                                <tr>
                                                    <td></td>
                                                    <td>Part</td>
                                                    <td>{{ $part->part }}</td>
                                                    <td class="text-right">
                                                        {{ $part->pivot->quantity }}
                                                    </td>
                                                    <td class="text-right">
                                                       {{ $part->price }}
                                                    </td>
                                                    <td class="text-right">
                                                        @if(($part->pivot->price != 0) && ($part->price != $part->pivot->price))
                                                            <div class="highlight">
                                                                {{ $part->pivot->price }}
                                                            </div>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        @if($part->pivot->price != 0)
                                                            {{ number_format($part->pivot->price * $part->pivot->quantity,2) }}
                                                        @else
                                                            {{ number_format($part->price * $part->pivot->quantity,2) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @endif

                                        {{-- Extra parts --}}
                                        @if($entry->extraParts->count() > 0)

                                            @foreach ($entry->extraParts as $part)
                                            <?php
                                                /**
                                                 * Calculate extra parts sum
                                                 */
                                                $extra_parts_sum += $part->price * $part->quantity;
                                            ?>
                                                <tr>
                                                    <td></td>
                                                    <td>Extra Part</td>
                                                    <td>{{ $part->part }}</td>
                                                    <td class="text-right">
                                                        {{ $part->quantity }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $part->price }}
                                                    </td>
                                                    <td class="text-right">
                                                        N/A
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($part->price * $part->quantity, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @endif

                                        {{-- @if(count($entry->media))
                                        <tr>
                                            <td colspan="6">
                                                
                                                <div class="masonry">
                                                <div class="container-fluid">
                                                <div class="row">
                                                @foreach ($entry->media as $file)
                                                    <div class="item">
                                                      <div class="well"> 
                                                        <img src="{{ asset('public/uploads/'.$file->media) }}" alt="Proposal Media" class="img-thumbnail" style="width: 100px;">
                                                      </div>
                                                    </div>
                                                @endforeach
                                                </div>
                                                </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                        @endif --}}

                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <!-- <tr>
                                            <td colspan="3" class="text-right">
                                                <strong>Total</strong>
                                            </td>
                                            <td class="text-right new-list-price">
                                                {{-- {{ number_format(
                                                    $proposal->proposalEntries()->sum('list_price')
                                                    + $original_parts_sum
                                                    + $extra_parts_sum
                                                    , 2)  
                                                }} --}}
                                            </td>
                                            <td class="text-right">
                                                {{-- {{ number_format(
                                                    $proposal->proposalEntries()->sum('extended_price')
                                                    + $pivot_sum
                                                    , 2) 
                                                }} --}}
                                            </td>
                                        </tr> -->
                                        {{-- Total amounts --}}
                                        <tr>
                                            <td colspan="6" class="text-right">
                                                <strong>Total</strong>
                                            </td>
                                            
                                            <td class="text-right" colspan="1">
                                                @php
                                                    $total_price = $parts_sum 
                                                                    + $extra_parts_sum
                                                                    + $item_sum;
                                                @endphp
                                                {{ number_format($total_price, 2)  }}
                                            </td>
                                        </tr>
                                        {{-- @if($proposal->discount > 0) --}}
                                        <tr>
                                            <td colspan="6" class="text-right">
                                                <strong><i>Discount ({{ $proposal->discount }}% - Only for labour charge)</i></strong>
                                            </td>
                                            <td class="text-right">
                                            
                                            @php
                                                $total_discount = $item_sum * ($proposal->discount/100);
                                                $net_price = $total_price - $total_discount;
                                            @endphp
                                                - {{ number_format($total_discount, 2) }}
                                            </td>
                                        </tr>
                                        <tr>    
                                            <td colspan="6" class="text-right">
                                                <strong>Net Amount</strong>
                                            </td>
                                            <td class="text-right">
                                                <strong>{{ number_format($net_price,2) }}</strong>
                                            </td>
                                        </tr>
                                        {{-- @endif --}}
                                    </tfoot>
                                </table>

                                </div>
                                @endif
                                {{-- edn of Proposal Details Table --}}

                                </div>
                            </div>
                        </div>

                        {{-- end of proposal details --}}
                        <div class="col-md-12 text-center">

                        @if($proposal->approved == "Yes")
                            {!! Form::open(['route' => 'proposals.approve', 'id' => 'approval-form']) !!}
                                {!! Form::hidden('proposal_id', $proposal->id) !!}
                                <button type="submit" class="btn btn-success click-to-approve">
                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                    Approve
                                </button>
                            {!! Form::close() !!}
                            <!-- <button type="button" class="btn btn-success approve-button" disabled>
                                <i class="fa fa-check" aria-hidden="true"></i>
                                Approved
                            </button> -->
                        @else
                            {!! Form::open(['route' => 'proposals.approve', 'id' => 'approval-form']) !!}
                                {!! Form::hidden('proposal_id', $proposal->id) !!}
                                <button type="submit" class="btn btn-success click-to-approve">
                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                    Approve
                                </button>
                            {!! Form::close() !!}
                        @endif
                            <a href="{{ route('admin.proposals.edit', $proposal) }}" class="btn btn-primary">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
{!! Html::script('public/js/jquery.noty.packaged.js') !!}
{!! Html::script('public/js/mp.mansory.js') !!}
<script>

    $(document).on('click', '.click-to-approve', function(e){
        e.preventDefault();
        var form = $(this).parent('form');
        generateAlert('center', form);
    });

    $('#approval-form').submit(function(e) {
        e.preventDefault();

        $('#approval-form button').html('<i class="fa fa-spinner fa-spin fa-fw"></i> Saving...').prop('disabled', true);

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
                $('#approval-form button').html('<i class="fa fa-check" aria-hidden="true"></i> Approve').prop('disabled', false);
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

    $('.tiny-box').blur(function() {
        var list_price = $('.tiny-box.list_price');

        var list_sum = 0;

        list_price.each(function(){
            
            list_sum += parseFloat(this.value);
            
        });

        $('td.new-list-price').text(list_sum.toFixed(2));
    });

    $(function(){
        $("#my-gallery-container").mpmansory(
            {
                childrenClass: 'item', // default is a div
                columnClasses: 'padding', //add classes to items
                breakpoints:{
                    lg: 4, 
                    md: 4, 
                    sm: 6,
                    xs: 12
                },
                distributeBy: { order: false, height: false, attr: 'data-order', attrOrder: 'asc' }, //default distribute by order, options => order: true/false, height: true/false, attr => 'data-order', attrOrder=> 'asc'/'desc'
                onload: function (items) {
                    //make somthing with items
                } 
            }
        );
    });

</script>
@endpush
