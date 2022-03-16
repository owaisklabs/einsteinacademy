<!-- need to remove -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-user"></i>
        <p>
             User Mangement
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="display: none;">
        <li class="nav-item">
            <a href="{{route('home')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('report-user')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Repoted Users </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('block-user')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Block Users</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ route('past-paper.index') }}" class="nav-link <?php if ($_SERVER['REQUEST_URI'] == '/home') {
    echo 'active';
} ?> ">
        <i class="nav-icon fa fa-paperclip"></i>
        <p>Past Papers</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('content.index') }}" class="nav-link <?php if ($_SERVER['REQUEST_URI'] == '/content') {
    echo 'active';
} ?> ">
        <i class="nav-icon fas fa-cogs"></i>
        <p>Content Mangement </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('push-notification.index') }}" class="nav-link <?php if ($_SERVER['REQUEST_URI'] == '/push-notification') {
    echo 'active';
} ?>  ">
        <i class="nav-icon fas fa-bullhorn"></i>
        <p>Push Notification</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('report-analytics.index') }}" class="nav-link <?php if ($_SERVER['REQUEST_URI'] == '/report-analytics') {
    echo 'active';
} ?> ">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>Report and Analytics</p>
    </a>
</li>
