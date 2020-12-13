<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <i class="nav-icon icon-speedometer"></i> Panel de Control
                </a>
            </li>
            @can('read-users','read-roles')
            <li class="nav-title">Settings</li>
            @endcan
            @can('read-customers')
            <li class="nav-item">
                <a class="nav-link" href="/customers">
                    <i class="nav-icon icon-organization"></i> Clientes
                </a>
            </li>
            @endcan
            @can('read-services')
            <li class="nav-item">
                <a class="nav-link" href="/services">
                    <i class="nav-icon icon-event"></i> Servicios
                </a>
            </li>
            @endcan
            @can('read-users')
            <li class="nav-item">
                <a class="nav-link" href="/users">
                    <i class="nav-icon icon-people"></i> Usuarios
                </a>
            </li>
            @endcan
            @can('read-roles')
            <li class="nav-item">
                <a class="nav-link" href="/roles">
                    <i class="nav-icon icon-key"></i> Roles
                </a>
            </li>
            @endcan
        </ul>
    </nav>
    <sidebar></sidebar>
</div>
