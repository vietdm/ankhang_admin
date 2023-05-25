
<ul class="nav-main">
    <li>
        <a href="/" class="{{ request()->getPathInfo() == '/' ? 'active' : '' }}">
            <i class="si si-handbag"></i>
            <span class="sidebar-mini-hide">Dashboard</span>
        </a>
    </li>
    @if (admin()->allow('confirm_order'))
        <li>
            <a href="/order/confirm" class="{{ request()->getPathInfo() == '/order/confirm' ? 'active' : '' }}">
                <i class="si si-handbag"></i>
                <span class="sidebar-mini-hide">Xác nhận đơn hàng</span>
            </a>
        </li>
    @endif
    @if (admin()->allow('confirm_withdraw'))
        <li>
            <a href="/withdraw/confirm" class="{{ request()->getPathInfo() == '/withdraw/confirm' ? 'active' : '' }}">
                <i class="si si-handbag"></i>
                <span class="sidebar-mini-hide">Rút tiền</span>
            </a>
        </li>
    @endif
</ul>
