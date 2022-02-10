<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link <?php if ($_SERVER['REQUEST_URI'] == "/home") {
        echo"active";
    } ?> ">
        <i class="nav-icon fa fa-users"></i>
        <p>User Mangement</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('content.index') }}" class="nav-link <?php if ($_SERVER['REQUEST_URI'] == "/content") {
        echo"active";
    } ?> ">
        <i class="nav-icon fas fa-cogs"></i>
        <p>Content Mangement  </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('push-notification.index') }}" class="nav-link <?php if ($_SERVER['REQUEST_URI'] == "/push-notification") {
        echo"active";
    } ?>  ">
        <i class="nav-icon fas fa-bullhorn"></i>
        <p>Push Notification</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('report-analytics.index') }}" class="nav-link <?php if ($_SERVER['REQUEST_URI'] == "/report-analytics") {
        echo"active";
    } ?> ">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>Report and Analytics</p>
    </a>
</li>
