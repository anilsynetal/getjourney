<ul class="nav nav-pills nav-fill custom-nav-line mb-4" id="pills-tab" role="tablist">
    <li class="nav-item">
        <a href="javascript:void(0);" class="nav-link {{ $tab_name == 'database' ? 'active' : '' }}">
            Database
        </a>
    </li>
    <li class="nav-item">
        <a href="javascript:void(0);" class="nav-link {{ $tab_name == 'requirement' ? 'active' : '' }}">
            Requirements
        </a>
    </li>
    <li class="nav-item">
        <a href="javascript:void(0);" class="nav-link {{ $tab_name == 'permissions' ? 'active' : '' }}">
            Permissions
        </a>
    </li>
    <li class="nav-item">
        <a href="javascript:void(0);" class="nav-link {{ $tab_name == 'verify' ? 'active' : '' }}">
            Verify
        </a>
    </li>
    <li class="nav-item">
        <a href="javascript:void(0);" class="nav-link {{ $tab_name == 'finish' ? 'active' : '' }}">
            Finish
        </a>
    </li>
</ul>
