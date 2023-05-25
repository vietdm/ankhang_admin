
<ul class="nav-main">
    <li>
        <a href="/" class="{{ request()->getPathInfo() == '/' ? 'active' : '' }}">
            <i class="si si-chart"></i>
            <span class="sidebar-mini-hide">Dashboard</span>
        </a>
    </li>
    @if (admin()->allow('all_order'))
        <li>
            <a href="/order/all" class="{{ request()->getPathInfo() == '/order/all' ? 'active' : '' }}">
                <i class="si si-organization"></i>
                <span class="sidebar-mini-hide">Tất cả đơn hàng</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('confirm_order'))
        <li>
            <a href="/order/confirm" class="{{ request()->getPathInfo() == '/order/confirm' ? 'active' : '' }}">
                <i class="si si-basket-loaded"></i>
                <span class="sidebar-mini-hide">Xác nhận đơn hàng</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('transfer_order'))
        <li>
            <a href="/order/transfer" class="{{ request()->getPathInfo() == '/order/transfer' ? 'active' : '' }}">
                <i class="si si-share-alt"></i>
                <span class="sidebar-mini-hide">Vận chuyển đơn hàng</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('confirm_withdraw'))
        <li>
            <a href="/withdraw/confirm" class="{{ request()->getPathInfo() == '/withdraw/confirm' ? 'active' : '' }}">
                <i class="si si-wallet"></i>
                <span class="sidebar-mini-hide">Rút tiền</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('settings'))
        <li>
            <a href="/settings" class="{{ request()->getPathInfo() == '/settings' ? 'active' : '' }}">
                <i class="si si-settings"></i>
                <span class="sidebar-mini-hide">Cài đặt</span>
            </a>
        </li>
    @endif
</ul>
