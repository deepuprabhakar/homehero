@extends('admin.app')

@section('title')
    {{ env('APP_NAME') }} - Dashboard
@endsection 

@section('content')
<div class="container">
    <div class="row">

        @include('admin.partials.sidebar')

        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    Welcome {{ Auth::guard('admin')->user()->name }}!
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <a class="menu-cards" href="{{ route('admin.proposals.index') }}">
                        <h3>Proposals</h3>
                        <i class="fa fa-file-text fa-5x"></i>
                        <h2>{{ number_format($proposals) }}</h2>   
                    </a>
                </div>
                <div class="col-md-4">
                    <a class="menu-cards" href="{{ route('admin.clients.index') }}">
                        <h3>Clients</h3>
                        <i class="fa fa-user fa-5x"></i>
                        <h2>{{ number_format($clients) }}</h2>    
                    </a>
                </div>
                <div class="col-md-4">
                    <a class="menu-cards" href="{{ route('admin.staff.index') }}">
                        <h3>Field staff</h3>
                        <i class="fa fa-briefcase fa-5x"></i>
                        <h2>{{ number_format($staff) }}</h2>    
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
