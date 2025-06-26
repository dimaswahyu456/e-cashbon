<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{url('/dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-cashbon.png') }}" alt="" height="30" style="margin-top: 20;">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/e-cashbon.png') }}" alt="" height="80">
            </span>
        </a>

        <a href="{{url('/dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-cashbon_dark.png') }}" alt="" height="30" style="margin-top: 20;">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/e-cashbon_dark.png') }}" alt="" height="80">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">@lang('translation.Menu')</li>

                <li class="{{ request()->is('dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ url('/dashboard') }}">
                        <i class="uil-home-alt"></i>
                        <span>@lang('translation.Dashboard')</span>
                    </a>
                </li>
                
                @if (Auth::user()->role_id == '1')
                <li class="{{ request()->is('master*') ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->is('master*') ? 'active' : '' }}">
                        <i class="uil-database-alt"></i>
                        <span>@lang('Data Master')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li class="{{ request()->routeIs('warehouse.list') ? 'active' : '' }}">
                            <a href="{{ route('warehouse.list') }}">@lang('Data Warehouse')</a>
                        </li>
                        <li class="{{ request()->routeIs('supplier.list') ? 'active' : '' }}">
                            <a href="{{ route('supplier.list') }}">@lang('Data Supplier')</a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="{{ request()->is('tagihan*') ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->is('tagihan*') ? 'active' : '' }}">
                        <i class="uil-moneybag-alt"></i>
                        <span>@lang('Tagihan')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li class="{{ request()->routeIs('cashbon.list') ? 'active' : '' }}">
                            <a href="{{ route('cashbon.list') }}">@lang('Data Cashbon')</a>
                        </li>
                        <li class="{{ request()->routeIs('done.list') ? 'active' : '' }}">
                            <a href="{{ route('done.list') }}">@lang('Data Cashbon Done')</a>
                        </li>
                    </ul>
                </li>
                
                @if (Auth::user()->role_id == '1')
                <li class="{{ request()->is('approved*') ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->is('approved*') ? 'active' : '' }}">
                        <i class="uil-envelope-check"></i>
                        <span>@lang('Approved')</span>                        
                        <span class="badge rounded-pill bg-success float-end"> {{ $countapprove }}</span>
                        <span class="badge rounded-pill bg-danger float-end"> {{ $countreject }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li class="{{ request()->routeIs('approved.list') ? 'active' : '' }}">
                            <a href="{{ route('approved.list') }}">@lang('Data Belum Approved')</a>
                        </li>
                        <li class="{{ request()->routeIs('acc.list') ? 'active' : '' }}">
                            <a href="{{ route('acc.list') }}">@lang('Data Sudah Terapproved')</a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="{{ request()->is('user*') ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->is('user*') ? 'active' : '' }}">
                        <i class="uil-users-alt"></i>
                        <span>@lang('Users')</span>
                    </a>
                    @if (Auth::user()->role_id == '1')
                    <ul class="sub-menu" aria-expanded="{{ request()->is('user*') ? 'true' : 'false' }}">
                        <li class="{{ request()->routeIs('user.list') ? 'active' : '' }}">
                            <a href="{{ route('user.list') }}">@lang('Data User')</a>
                        </li>
                    </ul>
                    @endif
                    <ul class="sub-menu" aria-expanded="{{ request()->is('user*') ? 'true' : 'false' }}">
                        <li class="{{ request()->routeIs('user.edit') ? 'active' : '' }}">
                            <a href="{{ route('user.edit',Auth::user()->id) }}">@lang('Change Password')</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->