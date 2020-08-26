<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<title>Proposal PDF</title>
	
	<style>
		@page { margin: 180px 50px 50px; }
	    #header { position: fixed; left: 0px; top: -130px; right: 0px; height: 250px; background-color: #fff; text-align: center; }
	    #footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px; border-top: 2px solid #ccc; }
	    #content{ margin-top: 130px; }
		.full-width{
			width: 100%;
		}
		.bold{
			font-weight: bold;
		}
		body{
			color: #666;
			font-family: 'sans-serif';
			font-size: 14px;
		}
		table.proposal-table{
			margin-top: 15px;
		}
		table.proposal-table th{
			background: #777;
			color: #fff;
			padding: 10px;
			border: 1px solid;
		}
		table.proposal-table td{
			padding: 10px;
			border-bottom: 2px solid #ccc;
		}
		.total-table .td-total span.span-total{
			border: 3px solid #ccc;
			padding: 10px 8px;
			width: 100%;
			display: block;
			font-weight: 600;
		}
		.total-table{
			margin-bottom: 0;
			position: relative;
			top: 550px;
			bottom: 0;
			/* height: 100%; */
		}
		#advance-amount{
			position: relative;
			top: 150px;
		}
	</style>
</head>
<body style="">

	<div id="header" style="padding-bottom: 20px;">
		{{-- Header Table --}}
	  	<table style="width: 100%" border="0">
	  		<tr>
	  			<td style="width: 50%" valign="top">
	  				{{ Html::image('/public/homehero.png', 'Home Hero Logo', ['style' => 'width: 300px;']) }}
	  				
	  				<p style="font-size: 20px" class="bold">
	  					{{ $proposal->first_name }} {{ $proposal->last_name }}
	  				</p>
	  				<p>
	  					{{ $proposal->client->first_address }}
	  					<br>
	  					{{ $proposal->client->second_address }}
	  				</p>
	  			
	  			</td>
	  			<td style="width: 50%" valign="top">
	  				
	  				{{-- Home here details --}}
	  				<table style="width: 100%" cellpadding="4" border="0">
	  					<tr>
	  						<td align="right" class="bold" style="font-size: 20px">Estimate</td>
	  						<td style="font-size: 20px">#{{ $proposal->id }}</td>
	  					</tr>
	  					<tr>
	  						<td align="right" class="bold" valign="top">From</td>
	  						<td valign="top">
	  							<div class="bold">The Home Hero</div>
	  								267-291-HERO<br>
	  								Sidekick@TheHomeHero.com<br>
	  								www.TheHomeHero.com<br>
	  								306 Fulton Street<br>
	  								Philadelphia, PA 19147<br>
	  						</td>
	  					</tr>
	  					<tr>
	  						<td align="right" valign="top">Bill To</td>
	  						<td>
	  							{{ nl2br($address) }}
	  						</td>
	  					</tr>
	  					<tr>
	  						<td align="right">Sent On</td>
	  						<td>{{ date('d/m/Y') }}</td>
	  					</tr>
	  					<tr>
	  						<td align="right" width="80">Job Description</td>
	  						<td>Ver 1.0</td>
	  					</tr>

	  				</table>

	  			</td>
	  		</tr>
	  	</table>
	</div>

	<div id="footer">
		<p class="page"></p>
	</div>

	<div id="content">
		{{-- Proposal Table --}}
		<table cellspacing="0" style="width: 100%; margin-top: 15px;" class="full-width proposal-table">
			<thead>
				<tr>
					<th align="left">Service/Product</th>
					<th align="left">Description</th>
					<th>Qty</th>
					<th align="right">Unit Cost</th>
					<th align="right">Total</th>
				</tr>
			</thead>
			<tbody>
			@php
				$total = 0;
				$total_labour_cost = 0;
			@endphp

			@foreach ($proposal->proposalEntries as $entry)

				@php
					$parts_sum = 0;
		            $extra_parts_sum = 0;
		            $original_parts_sum = 0;
		            $item_sum = 0;
		            $item_total = 0;
				@endphp

				@php
					/**
					 * Calcutate work item sum
					 */
					if($entry->extended_price != 0)
					    $item_sum += $entry->extended_price;
					else
					    $item_sum += $entry->list_price;


					if($entry->parts->count() > 0)
					{
						foreach ($entry->parts as $part)
						{
							/**
							 * Calculate parts sum
							 */
							if($part->pivot->price != 0)
							    $parts_sum += ($part->pivot->price * $part->pivot->quantity);
							else
							    $parts_sum += ($part->price * $part->pivot->quantity);
						}
					}

					if($entry->extraParts->count() > 0)
					{
						/**
						 * Calculate extra parts sum
						 */
						foreach ($entry->extraParts as $part)
							$extra_parts_sum += $part->price * $part->quantity;
					}

					$item_total = number_format(
		                      $parts_sum 
		                    + $extra_parts_sum
		                    + $item_sum 
		                    , 2);

				@endphp

				<tr style="border-bottom: 1px solid #ccc;">
					<td valign="top">
						{{ $entry->workItem->itemType->type }}
					</td>
					<td valign="top">
						
						<div>			
							{{ $entry->location->sub_type }} 
							- {{ $entry->location->type }} 
							- {{ $entry->room->area }}
						</div>
						
						<div>
							- {{ $entry->workItem->detail }}
						</div>

						@if(count($entry->sortedSteps()) > 0)
							@foreach ($entry->sortedSteps() as $step)
								<div>- {{ $step['step'] }}</div>
							@endforeach
						@endif

						{{-- @if(count($entry->steps) > 0)
							@foreach ($entry->steps as $step)
								<div>- {{ $step->detail }}</div>
							@endforeach
						@endif

						@if(count($entry->extraSteps) > 0)
							@foreach ($entry->extraSteps as $step)
								<div>- {{ $step->step }}</div>
							@endforeach
						@endif --}}
						
						{{-- @if($entry->notes != "")
							<div>
								* {{ $entry->notes }}
							</div>
						@endif --}}

						{{-- display work-item notes --}}
						@if(count($entry->workItem->notes) > 0)
							@foreach ($entry->workItem->notes as $note)
								<div>* {{ $note->note }}</div>
							@endforeach
						@endif

					</td>
					<td valign="top" align="center">
						{{ $entry->quantity }}
					</td>
					<td valign="top" align="right">
						<span>${{ $item_total }}</span>

		                @php
		                	$total += ($parts_sum + $extra_parts_sum + $item_sum) * $entry->quantity;
		                	$total_labour_cost += $item_sum * $entry->quantity;
		                @endphp
					</td>
					<td valign="top" align="right">
						${{ number_format($item_total * $entry->quantity,2) }}
					</td>
					
				</tr>
			@endforeach
				{{-- Total amounts --}}
                <tr style="border-bottom: 1px solid #ccc;">
                    <td colspan="4" class="text-right">
                        <strong><i>Discount ({{ $proposal->discount }}% - Only for labour charge)</i></strong>
                    </td>
                    @php
                        $net_price = $total - ( ($total_labour_cost) * ($proposal->discount/100) );
                    @endphp
                    <td valign="top" align="right">
                    	- ${{ number_format($total_labour_cost * ($proposal->discount/100), 2) }}
                    </td>
                </tr>
                
                {{-- /Total amounts --}}
			</tbody>
		</table>
	</div>
	
	<p style="page-break-before: always;">
	<div align="center" class="bold " id="advance-amount">
		A deposit of ${{ number_format($net_price/2, 2) }} will be required to begin.
	</div>
	<table class="full-width total-table" style="width: 100%;" border="0">
		<tr>
			<td valign="" style="width: 70%"">
				<div style="font-size: 13px;">
					Estimate Prices Good For 30 Days
				</div>
				<p style="font-size: 13px;">
					Payments of the Contract price shall be paid in the following manner: 50%
					upfront, remaining balance upon completion. All receipts for supplies will be
					provided to homeowner and any supply costs over the estimate must be
					reimbursed within two business days.
				</p>
				<p style="font-size: 13px;">
					The Home Hero Warranty's all craftsmanship for up to 6 months.
				</p>
				<p style="font-size: 13px;">
					Exact Start Date/Duration To Be Determined
				</p>
				<p style="font-size: 13px;">
					All Major Credit Cards and Personal Checks Accepted
				</p>
			</td>
			<td class="bold" align="right" valign="top" style="width: 10%">
				<div style="margin-top: 10px">Total</div>
			</td>
			<td align="right" valign="top" class="td-total bold">
				 <span class="span-total">${{ number_format($net_price,2) }}</span>
			</td>
		</tr>
	</table>
	</p>
	
	<script type="text/php">
        if ( isset($pdf) ) {
            $font = $fontMetrics->get_font("helvetica", "", "#ccc");
            $pdf->page_text(518, 760, "{PAGE_NUM} of {PAGE_COUNT} pages", $font, 10, array(.5,.5,.5));
        }
	</script>

</body>
</html>