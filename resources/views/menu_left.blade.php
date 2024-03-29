@php
    $path = request()->getPathInfo();
    $uri = request()->route()->uri;
@endphp
@php
    $activeWhen = function ($p) use ($path) {
        return $p == $path ? 'active' : '';
    };
@endphp
<ul class="nav-main">
    <li class="{{ str_starts_with($path, '/dashboard') || $path == '/' ? 'open' : '' }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="si si-chart"></i>
            <span class="sidebar-mini-hide">Dashboard</span>
        </a>
        <ul>
            <li>
                <a href="/" class="{{ $activeWhen('/') }}">Tổng quan</a>
            </li>
            <li>
                <a href="/dashboard/bonus" class="{{ $activeWhen('/dashboard/bonus') }}">Hoa hồng</a>
            </li>
        </ul>
    </li>
    @if (admin()->allow('view_user'))
        <li class="{{ str_starts_with($path, '/user') ? 'open' : '' }}">
            <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                <i class="si si-user"></i>
                <span class="sidebar-mini-hide">Người dùng</span>
            </a>
            <ul>
                <li>
                    <a href="/user/all" class="{{ $activeWhen('/user/all') }}">Tất cả người dùng</a>
                </li>
                @if ($uri === 'user/{id}')
                    <li>
                        <a href="/user/{{ request()->id }}" class="active">Chi tiết người dùng</a>
                    </li>
                @endif
            </ul>
        </li>
    @endif
    <li class="{{ str_starts_with($path, '/order') ? 'open' : '' }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="si si-organization"></i>
            <span class="sidebar-mini-hide">Đơn hàng</span>
        </a>
        <ul>
            @if (admin()->allow('all_order'))
                <li>
                    <a href="/order/all" class="{{ $activeWhen('/order/all') }}">Tất cả đơn hàng</a>
                </li>
            @endif
            @if (admin()->allow('confirm_order'))
                <li>
                    <a href="/order/confirm" class="{{ $activeWhen('/order/confirm') }}">Xác nhận đơn hàng</a>
                </li>
            @endif
            @if (admin()->allow('transfer_order'))
                <li>
                    <a href="/order/transfer" class="{{ $activeWhen('/order/transfer') }}">Vận chuyển đơn hàng</a>
                </li>
            @endif
            @if (admin()->allow('create_order'))
                <li>
                    <a href="/order/create" class="{{ $activeWhen('/order/create') }}">Tạo đơn thủ công</a>
                </li>
            @endif
        </ul>
    </li>
    @if (admin()->allow('confirm_withdraw'))
        <li>
            <a href="/withdraw/confirm" class="{{ $activeWhen('/withdraw/confirm') }}">
                <i class="si si-wallet"></i>
                <span class="sidebar-mini-hide">Rút tiền</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('akg'))
        <li class="{{ str_starts_with($path, '/akg') ? 'open' : '' }}">
            <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                <i class="si si-wallet"></i>
                <span class="sidebar-mini-hide">Điểm AKG</span>
            </a>
            <ul>
                <li>
                    <a href="/akg/all" class="{{ $activeWhen('/akg/all') }}">Tổng quan</a>
                </li>
                <li>
                    <a href="/akg/transfer" class="{{ $activeWhen('/akg/transfer') }}">Chuyển AKG</a>
                </li>
            </ul>
        </li>
    @endif
    @if (admin()->allow('all'))
        <li>
            <a href="/lucky-event" class="{{ $activeWhen('/lucky-event') }}">
                <i class="fa fa-gift"></i>
                <span class="sidebar-mini-hide">Vòng quay may mắn</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('all'))
        <li>
            <a href="/accounts" class="{{ $activeWhen('/accounts') }}">
                <i class="si si-user"></i>
                <span class="sidebar-mini-hide">Quản lý tài khoản ADMIN</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('settings'))
        <li>
            <a href="/settings" class="{{ $activeWhen('/settings') }}">
                <i class="si si-settings"></i>
                <span class="sidebar-mini-hide">Cài đặt</span>
            </a>
        </li>
    @endif
</ul>
