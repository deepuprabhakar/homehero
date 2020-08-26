<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Home Hero Proposal</title>
	
	</head>
	<style>
		body{
			font-family: 'Lato', sans-serif;
		}
	</style>
	<body>
		<table style="max-width:700px; margin:0 auto; font-family: arial; font-size: 12px; line-height: 20px; background-color: #f4f4f4; padding:0px;" cellpadding="0" cellspacing="0">
		<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr style="background-color: #ffd800;">
					<td colspan="3">
						<table width="100%">
							<tr>
								<td valign="top" style="padding:30px 20px;">
						{{ Html::image('/public/homehero.png', 'Home Hero Logo', ['style' => 'width: 240px;']) }}
					</td>
					<td align="left" width="70%">
						<h3 style="font-size: 24px; line-height: 18px; font-weight: 100; margin:0px; padding:0px;">
							{{ $proposal->first_name }} {{ $proposal->last_name }}
						</h3>
						<p>
							{{ $proposal->client->first_address }}
		  					<br>
		  					{{ $proposal->client->second_address }}
						</p>
					</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="padding:20px;"><h2 style="margin:0px;">
						Estimate - #{{ $proposal->id }}</h2>
					</td>
				</tr>
				<tr>
					<td valign="top" colspan="2" style="padding:0px 20px;">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" style="font-weight: bold;">From</td>
								<td valign="top">
									<P style="margin:0px;">The Home Hero<br>
										267-291-Hero<br>
										Sidekick@thehomehero.com<br>
										www.thehomehero.com<br>
										306 Fulton Street<br>
									Philadelphia, PA 19147</P>
								</td>
							</tr>
						</table>
					</td>
					<td valign="top" style="padding:0px;">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td style="font-weight: bold;">Bill To</td>
								<td>: {{ nl2br($address) }}</td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Sent On</td>
								<td>: {{ date('d/m/Y') }}</td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Job Description</td>
								<td>: Ver 1.0</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding:20px;">
						<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;">
							<thead style="background-color: #777; color: #fff;">
								<tr>
									<td style="padding:10px;">Service/Product</td>
									<td>Description</td>
									<td align="center">Qty</td>
									<td align="right">Unit Cost</td>
									<td align="right" style="padding-right: 10px;">Total</td>
								</tr>
								</thead>
								
								@php
									$total = 0;
									$total_labour_cost = 0;
								@endphp
								
								<tbody>
								
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

								

								<tr>
									<td style="padding:10px; border-bottom:1px solid #e0e0e0;" valign="top">
										{{ $entry->workItem->itemType->type }}
									</td>
									<td style="border-bottom:1px solid #e0e0e0;">
										{{ $entry->location->sub_type }} 
										- {{ $entry->location->type }} 
										- {{ $entry->room->area }}
										<div>- {{ $entry->workItem->detail }}</div>

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
										
										@if($entry->notes != "")
											<div>
												* {{ $entry->notes }}
											</div>
										@endif
									</td>
									<td align="center" valign="top" style="border-bottom:1px solid #e0e0e0;">
										{{ $entry->quantity }}
									</td>
									<td align="right" valign="top" style="border-bottom:1px solid #e0e0e0;">
										${{ $item_total }}
									</td>

									@php
										$total += ($parts_sum + $extra_parts_sum + $item_sum) * $entry->quantity;
										$total_labour_cost += $item_sum * $entry->quantity;
									@endphp
									
									<td align="right" valign="top" style="border-bottom:1px solid #e0e0e0; padding-right: 10px;">
										${{ number_format($item_total * $entry->quantity,2) }}
									</td>
								</tr>
								@endforeach
								{{-- Total amounts --}}
				                <tr >
				                    <td colspan="4" class="text-right" style="border-bottom: 1px solid #ccc; padding:10px;" valign="top">
				                        <strong><i>Discount ({{ $proposal->discount }}% - Only for labour charge)</i></strong>
				                    </td>
				                    @php
				                        $net_price = $total - ( $total_labour_cost * ($proposal->discount/100) );
				                    @endphp
				                    <td valign="middle" align="right" style="border-bottom:1px solid #e0e0e0; padding-right: 10px;">
				                    	- ${{ number_format($total_labour_cost * ($proposal->discount/100), 2) }}
				                    </td>
				                </tr>
				                
				                {{-- /Total amounts --}}
								</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding:20px;">
						<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff; padding:20px;">
							<tr>
								<td><div style="margin:0px 0px 20px 0px; font-size:14px;">
									* A deposit of ${{ number_format($net_price/2, 2) }} will be required to begin.
								</div></td>
							</tr>
							<tr>
								<td>
									<table width="100%">
										<tr>
											<td style="color: #777">Estimate Prices Good For 30 Days</td>
											<td style="font-size:18px;" align="right">
												Total 
												<span style="border:2px solid #777; padding:8px 10px;">		${{ number_format($net_price,2) }}
												</span>
											</td>
										</tr>
										<tr>
											<td colspan="2" style="color: #777">
												<p>Payments of the Contract price shall be paid in the following manner: 50% upfront,
												remaining balance upon completion. All receipts for supplies will be provided to
												homeowner and any supply costs over the estimate must be reimbursed within two
												business days.</p>
												<p>The Home Hero Warranty's all craftsmanship for up to 6 months.</p>
												<p>Exact Start Date/Duration To Be Determined</p>
												<p>All Major Credit Cards and Personal Checks Accepted</p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</td>
			</tr>
		</table>
	</body>
</html>