<!DOCTYPE html>
<html>
<head>
	<title>Proposal</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<!-- Styles -->
    <style>
    	.panel {
    	  margin-bottom: 20px;
    	  background-color: #fff;
    	  border: 1px solid transparent;
    	  border-radius: 4px;
    	  -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    	          box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    	}
    	.panel-body {
    	  padding: 15px;
    	}
    	.panel-heading {
    	  padding: 10px 15px;
    	  border-bottom: 1px solid transparent;
    	  border-top-left-radius: 3px;
    	  border-top-right-radius: 3px;
    	}
    	.panel-heading > .dropdown .dropdown-toggle {
    	  color: inherit;
    	}
    	.panel-title {
    	  margin-top: 0;
    	  margin-bottom: 0;
    	  font-size: 16px;
    	  color: inherit;
    	}
    	.panel-default {
    	  border-color: #ddd;
    	}
    	.panel-default > .panel-heading {
    	  color: #333;
    	  background-color: #f5f5f5;
    	  border-color: #ddd;
    	}
    	.panel-default > .panel-heading + .panel-collapse > .panel-body {
    	  border-top-color: #ddd;
    	}
    	.panel-default > .panel-heading .badge {
    	  color: #f5f5f5;
    	  background-color: #333;
    	}
    	.panel-default > .panel-footer + .panel-collapse > .panel-body {
    	  border-bottom-color: #ddd;
    	}
    	.panel-primary {
    	  border-color: #337ab7;
    	}
    	.panel-primary > .panel-heading {
    	  color: #fff;
    	  background-color: #337ab7;
    	  border-color: #337ab7;
    	}
    	table {
    	  border-spacing: 0;
    	  border-collapse: collapse;
    	}
    	td,
    	th {
    	  padding: 0;
    	}
    	@media print {
    	  *,
    	  *:before,
    	  *:after {
    	    color: #000 !important;
    	    text-shadow: none !important;
    	    background: transparent !important;
    	    -webkit-box-shadow: none !important;
    	            box-shadow: none !important;
    	  }
    	  a,
    	  a:visited {
    	    text-decoration: underline;
    	  }
    	  a[href]:after {
    	    content: " (" attr(href) ")";
    	  }
    	  abbr[title]:after {
    	    content: " (" attr(title) ")";
    	  }
    	  a[href^="#"]:after,
    	  a[href^="javascript:"]:after {
    	    content: "";
    	  }
    	  pre,
    	  blockquote {
    	    border: 1px solid #999;

    	    page-break-inside: avoid;
    	  }
    	  thead {
    	    display: table-header-group;
    	  }
    	  tr,
    	  img {
    	    page-break-inside: avoid;
    	  }
    	  img {
    	    max-width: 100% !important;
    	  }
    	  p,
    	  h2,
    	  h3 {
    	    orphans: 3;
    	    widows: 3;
    	  }
    	  h2,
    	  h3 {
    	    page-break-after: avoid;
    	  }
    	  .navbar {
    	    display: none;
    	  }
    	  .btn > .caret,
    	  .dropup > .btn > .caret {
    	    border-top-color: #000 !important;
    	  }
    	  .label {
    	    border: 1px solid #000;
    	  }
    	  .table {
    	    border-collapse: collapse !important;
    	  }
    	  .table td,
    	  .table th {
    	    background-color: #fff !important;
    	  }
    	  .table-bordered th,
    	  .table-bordered td {
    	    border: 1px solid #ddd !important;
    	  }
    	}
    	table {
    	  background-color: transparent;
    	}
    	caption {
    	  padding-top: 8px;
    	  padding-bottom: 8px;
    	  color: #777;
    	  text-align: left;
    	}
    	th {
    	  text-align: left;
    	}
    	.table {
    	  width: 100%;
    	  max-width: 100%;
    	  margin-bottom: 20px;
    	}
    	.table > thead > tr > th,
    	.table > tbody > tr > th,
    	.table > tfoot > tr > th,
    	.table > thead > tr > td,
    	.table > tbody > tr > td,
    	.table > tfoot > tr > td {
    	  padding: 8px;
    	  line-height: 1.42857143;
    	  vertical-align: top;
    	  border-top: 1px solid #ddd;
    	}
    	.table > thead > tr > th {
    	  vertical-align: bottom;
    	  border-bottom: 2px solid #ddd;
    	}
    	.table > caption + thead > tr:first-child > th,
    	.table > colgroup + thead > tr:first-child > th,
    	.table > thead:first-child > tr:first-child > th,
    	.table > caption + thead > tr:first-child > td,
    	.table > colgroup + thead > tr:first-child > td,
    	.table > thead:first-child > tr:first-child > td {
    	  border-top: 0;
    	}
    	.table > tbody + tbody {
    	  border-top: 2px solid #ddd;
    	}
    	.table .table {
    	  background-color: #fff;
    	}
    	.table-condensed > thead > tr > th,
    	.table-condensed > tbody > tr > th,
    	.table-condensed > tfoot > tr > th,
    	.table-condensed > thead > tr > td,
    	.table-condensed > tbody > tr > td,
    	.table-condensed > tfoot > tr > td {
    	  padding: 5px;
    	}
    	.table-bordered {
    	  border: 1px solid #ddd;
    	}
    	.table-bordered > thead > tr > th,
    	.table-bordered > tbody > tr > th,
    	.table-bordered > tfoot > tr > th,
    	.table-bordered > thead > tr > td,
    	.table-bordered > tbody > tr > td,
    	.table-bordered > tfoot > tr > td {
    	  border: 1px solid #ddd;
    	}
    	.table-bordered > thead > tr > th,
    	.table-bordered > thead > tr > td {
    	  border-bottom-width: 2px;
    	}
    	.table-striped > tbody > tr:nth-of-type(odd) {
    	  background-color: #f9f9f9;
    	}
    	.table-hover > tbody > tr:hover {
    	  background-color: #f5f5f5;
    	}
    	table col[class*="col-"] {
    	  position: static;
    	  display: table-column;
    	  float: none;
    	}
    	table td[class*="col-"],
    	table th[class*="col-"] {
    	  position: static;
    	  display: table-cell;
    	  float: none;
    	}
    	.page-break {
    	    page-break-after: always;
    	}
        table.table.proposal, .proposal-panel-no-m-b{
          margin-bottom: 0;
        }
        .proposal-staff div i.fa{
          margin-right: 5px;
          width: 16px;
          text-align: center;
        }
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
    </style>
</head>
<body>
	<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Proposal Details
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
                                @endif
                                {{-- edn of Staff Details Table --}}

                                </div>
                            </div>
                        </div>
                        <div class="page-break"></div>
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
                                            <td>{{ $proposal->address }}</td>
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
                                            <th colspan="5">Proposal Entries</th>
                                        </tr>
                                        <tr>
                                            <th>No.</th>
                                            <th>Type</th>
                                            <th>Details</th>
                                            <th class="text-right">List Price ($)</th>
                                            <th class="text-right">Ext Price ($)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            $parts_sum = 0;  
                                            $ext_sum = 0;
                                            $list_sum = 0
                                        ?>
                                        @foreach ($proposal->proposalEntries as $key => $entry)

                                            @if($entry->list_price != $entry->extended_price)
                                                <?php
                                                    $ext_sum += $entry->extended_price;
                                                    $list_sum += $entry->list_price;
                                                ?> 
                                            @endif
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
                                                    {{ $entry->list_price }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $entry->extended_price }}
                                                </td>
                                            </tr>

                                            {{-- parts --}}
                                            @if($entry->parts->count() > 0)

                                                <?php
                                                    $parts_sum += ($entry->parts->sum('price'));
                                                ?> 
                                                        
                                                @foreach ($entry->parts as $part)
                                                    <tr>
                                                        <td></td>
                                                        <td>Part</td>
                                                        <td>{{ $part->part }}</td>
                                                        <td class="text-right">
                                                            {{ $part->price }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format(0, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            
                                            @endif

                                            {{-- Extra parts --}}
                                            @if($entry->extraParts->count() > 0)

                                                <?php
                                                    $parts_sum += ($entry->extraParts->sum('price'));
                                                ?> 
                                            
                                                @foreach ($entry->extraParts as $part)
                                                    <tr>
                                                        <td></td>
                                                        <td>Extra Part</td>
                                                        <td>{{ $part->part }}</td>
                                                        <td class="text-right">
                                                            {{ $part->price }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format(0, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            
                                            @endif

                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-right">
                                                    <strong>Total</strong>
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($proposal->proposalEntries->sum('list_price') + $parts_sum, 2)  }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($proposal->proposalEntries->sum('extended_price'), 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-right">
                                                    <strong>Net</strong>
                                                </td>
                                                <td class="text-right" colspan="2">
                                                    {{ number_format($proposal->proposalEntries->sum('list_price') + $parts_sum + $ext_sum - $list_sum, 2)  }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </tbody>
                                </table>
                                </div>    
                                @endif
                                {{-- edn of Proposal Details Table --}}

                                </div>
                            </div>
                        </div>
                        {{-- end of proposal details --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>