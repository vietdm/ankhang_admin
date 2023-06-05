@php $path = request()->getPathInfo() @endphp
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
    @if (admin()->allow('all_order'))
        <li>
            <a href="/order/all" class="{{ $activeWhen('/order/all') }}">
                <i class="si si-organization"></i>
                <span class="sidebar-mini-hide">Tất cả đơn hàng</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('confirm_order'))
        <li>
            <a href="/order/confirm" class="{{ $activeWhen('/order/confirm') }}">
                <i class="si si-basket-loaded"></i>
                <span class="sidebar-mini-hide">Xác nhận đơn hàng</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('transfer_order'))
        <li>
            <a href="/order/transfer" class="{{ $activeWhen('/order/transfer') }}">
                <i class="si si-share-alt"></i>
                <span class="sidebar-mini-hide">Vận chuyển đơn hàng</span>
            </a>
        </li>
    @endif
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
    @if (admin()->allow('settings'))
        <li>
            <a href="/settings" class="{{ $activeWhen('/settings') }}">
                <i class="si si-settings"></i>
                <span class="sidebar-mini-hide">Cài đặt</span>
            </a>
        </li>
    @endif
</ul>
