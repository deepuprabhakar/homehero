<div class="col-md-3">
    <div class="list-group">
        <div class="list-group-item list-group-item-title">
          Menu
        </div>
      	<a href="{{ route('admin.dashboard') }}" class="list-group-item
      	{{ Request::is('admin/dashboard') ? ' active' : '' }}">
	        <i class="fa fa-tachometer pull-right" aria-hidden="true"></i>
	        Dashboard
      	</a>
      	<a href="{{ route('admin.admins.index') }}" class="list-group-item
      	{{ Request::is('admin/admins*') ? ' active' : '' }}">
	        <i class="fa fa-user-plus pull-right" aria-hidden="true"></i>
	        Admins
      	</a>
        <a href="{{ route('admin.staff.index') }}" class="list-group-item
        {{ Request::is('admin/staff*') ? ' active' : '' }}">
          <i class="fa fa-briefcase pull-right" aria-hidden="true"></i>
          Field Staff
        </a>
        <a href="{{ route('admin.clients.index') }}" class="list-group-item
        {{ Request::is('admin/clients*') ? ' active' : '' }}">
          <i class="fa fa-user pull-right" aria-hidden="true"></i>
          Clients
        </a>
        <a href="{{ route('admin.proposals.index') }}" class="list-group-item
        {{ Request::is('admin/proposals*') ? ' active' : '' }}">
          <i class="fa fa-file-text pull-right" aria-hidden="true"></i>
          Proposals
        </a>
    </div>

    <div class="list-group">
        <div class="list-group-item list-group-item-title">
          Admin System Setup
        </div>

        {{-- Work Item Menu --}}
        <a href="{{ route('admin.work-items.index') }}" class="list-group-item list-group-item-info grey
        {{ Request::is('admin/work-items*') ? ' active' : '' }}">
          <i class="fa fa-wrench pull-right" aria-hidden="true"></i>
          Work Items
        </a>
        
        {{-- Types --}}
        <a href="{{ route('admin.types.index') }}" class="list-group-item intended
        {{ Request::is('admin/types*') ? ' active' : '' }}">
          <i class="fa fa-th-large pull-right" aria-hidden="true"></i>
          <i class="fa fa-angle-right" aria-hidden="true"></i> Types
        </a>

        {{-- Sub Types --}}
        <a href="{{ route('admin.sub-types.index') }}" class="list-group-item intended
        {{ Request::is('admin/sub-types*') ? ' active' : '' }}">
          <i class="fa fa-th-large pull-right" aria-hidden="true"></i>
          <i class="fa fa-angle-right" aria-hidden="true"></i> Sub Types
        </a>

        {{-- Parts --}}
        <a href="{{ route('admin.parts.index') }}" class="list-group-item intended
        {{ Request::is('admin/parts*') ? ' active' : '' }}">
          <i class="fa fa-th-large pull-right" aria-hidden="true"></i>
          <i class="fa fa-angle-right" aria-hidden="true"></i> Parts
        </a>
        
        {{-- Steps --}}
        <a href="{{ route('admin.item-steps.index') }}" class="list-group-item intended
        {{ Request::is('admin/item-steps*') ? ' active' : '' }}">
          <i class="fa fa-th-large pull-right" aria-hidden="true"></i>
          <i class="fa fa-angle-right" aria-hidden="true"></i> Item Steps
        </a>

        {{-- Item Notes --}}
        <a href="{{ route('admin.item-notes.index') }}" class="list-group-item intended
        {{ Request::is('admin/item-notes*') ? ' active' : '' }}">
          <i class="fa fa-th-large pull-right" aria-hidden="true"></i>
          <i class="fa fa-angle-right" aria-hidden="true"></i> Item Notes
        </a>
        
        {{-- /end of Work Item Menu --}}

        <a href="{{ route('admin.locations.index') }}" class="list-group-item
        {{ Request::is('admin/locations*') ? ' active' : '' }}">
          <i class="fa fa-location-arrow pull-right" aria-hidden="true"></i>
          Locations
        </a>
        <a href="{{ route('admin.rooms.index') }}" class="list-group-item
        {{ Request::is('admin/rooms*') ? ' active' : '' }}">
          <i class="fa fa-square-o pull-right" aria-hidden="true"></i>
          Rooms
        </a>
        
    </div>
</div>