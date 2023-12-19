<!-- ======= Sidebar ======= -->
@php
$getPermission = Auth::user()->user_permissions ? explode(",", Auth::user()->user_permissions) : [];
@endphp
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @if(count($getPermission) == 0)
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('escorts/create') || request()->is('escorts') || request()->is('calendar/event/*') || request()->is('escorts/edit/*') || request()->is('escorts/view/*') ? '' : 'collapsed' }} " data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('escorts/create') || request()->is('escorts') || request()->is('calendar/event/*') || request()->is('escorts/edit/*') || request()->is('escorts/view/*') ? 'true' : 'false' }}">
                <i class="bi bi-person"></i><span>Escorts</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="tables-nav" class="nav-content collapse {{ request()->is('escorts/create') || request()->is('escorts') || request()->is('calendar/event/*') || request()->is('escorts/edit/*') || request()->is('escorts/view/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('admin.show.escorts') }}" class="{{ request()->is('escorts') ||request()->is('escorts') || request()->is('calendar/event/*') || request()->is('escorts/view/*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>All Escorts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.create.escorts') }}" class="{{ request()->is('escorts/create') || request()->is('escorts/edit/*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>New Escorts</span>
                        </a>
                    </li>
                </ul>
            </li>
        
            <li class="nav-item">
                <a class="nav-link {{ request()->is('bookings') ? '' : 'collapsed' }}" href="{{ route('admin.booking') }}">
                <i class="bi bi-bag"></i>
                    <span>Bookings</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('clients') ? '' : 'collapsed' }}" href="{{ route('admin.clients') }}">
                <i class="bi bi-person"></i>
                    <span>Clients</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('language') ? '' : 'collapsed' }}" href="{{ route('admin.language') }}">
                <i class="bi bi-translate"></i>
                    <span>All String</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings/privacy')|| request()->is('settings/terms') ? '' : 'collapsed' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('settings/privacy') || request()->is('settings/terms') ? 'true' : 'false' }}">
                <i class="bi bi-gear"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse {{ request()->is('settings/privacy')|| request()->is('settings/terms') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route ('admin.settings.privacypolicy')}}" class="{{ request()->is('settings/privacy')? 'active' : ''}}">
                        <i class="bi bi-circle"></i><span>Privacy Policy</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.termcondition') }}" class="{{ request()->is('settings/terms')? 'active' : ''}}">
                        <i class="bi bi-circle"></i><span>Terms Conditions</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('subadmin') || request()->is('subadmin/add') || request()->is('subadmin/edit') ? '' : 'collapsed' }} " data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('subadmin') || request()->is('subadmin/add') || request()->is('subadmin/edit') ? 'true' : 'false' }}">
                <i class="bi bi-person"></i><span>Sub Admin</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="forms-nav" class="nav-content collapse {{ request()->is('subadmin') ||  request()->is('subadmin/add') || request()->is('subadmin/edit') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('admin.subadmin') }}" class="{{ request()->is('subadmin')? 'active' : ''}}">
                        <i class="bi bi-circle"></i><span>List</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subadmin.add') }}" class="{{ request()->is('subadmin/add') || request()->is('subadmin/edit') ? 'active' : ''}}">
                        <i class="bi bi-circle"></i><span>Add</span>
                        </a>
                    </li>
                </ul>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
                </a>
            </li>
            @if(in_array('escorts', $getPermission))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('escorts/create') || request()->is('escorts') || request()->is('calendar/event/*') || request()->is('escorts/edit/*') || request()->is('escorts/view/*') ? '' : 'collapsed' }} " data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('escorts/create') || request()->is('escorts') || request()->is('calendar/event/*') || request()->is('escorts/edit/*') || request()->is('escorts/view/*') ? 'true' : 'false' }}">
                    <i class="bi bi-person"></i><span>Escorts</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="tables-nav" class="nav-content collapse {{ request()->is('escorts/create') || request()->is('escorts') || request()->is('calendar/event/*') || request()->is('escorts/edit/*') || request()->is('escorts/view/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('admin.show.escorts') }}" class="{{ request()->is('escorts') ||request()->is('escorts') || request()->is('calendar/event/*') || request()->is('escorts/view/*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>All Escorts</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.create.escorts') }}" class="{{ request()->is('escorts/create') || request()->is('escorts/edit/*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>New Escorts</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if(in_array('booking', $getPermission))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('bookings') ? '' : 'collapsed' }}" href="{{ route('admin.booking') }}">
                    <i class="bi bi-bag"></i>
                        <span>Bookings</span>
                    </a>
                </li>
            @endif
            @if(in_array('client', $getPermission))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('clients') ? '' : 'collapsed' }}" href="{{ route('admin.clients') }}">
                    <i class="bi bi-person"></i>
                        <span>Clients</span>
                    </a>
                </li>
            @endif
            @if(in_array('allstring', $getPermission))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('language') ? '' : 'collapsed' }}" href="{{ route('admin.language') }}">
                    <i class="bi bi-translate"></i>
                        <span>All String</span>
                    </a>
                </li>
            @endif
            @if(in_array('setting', $getPermission))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('settings/privacy')|| request()->is('settings/terms') ? '' : 'collapsed' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('settings/privacy') || request()->is('settings/terms') ? 'true' : 'false' }}">
                    <i class="bi bi-gear"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="components-nav" class="nav-content collapse {{ request()->is('settings/privacy')|| request()->is('settings/terms') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route ('admin.settings.privacypolicy')}}" class="{{ request()->is('settings/privacy')? 'active' : ''}}">
                            <i class="bi bi-circle"></i><span>Privacy Policy</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.termcondition') }}" class="{{ request()->is('settings/terms')? 'active' : ''}}">
                            <i class="bi bi-circle"></i><span>Terms Conditions</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if(in_array('subadmin', $getPermission))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('subadmin') || request()->is('subadmin/add') || request()->is('subadmin/edit') ? '' : 'collapsed' }} " data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('subadmin') || request()->is('subadmin/add') || request()->is('subadmin/edit') ? 'true' : 'false' }}">
                    <i class="bi bi-person"></i><span>Sub Admin</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="forms-nav" class="nav-content collapse {{ request()->is('subadmin') ||  request()->is('subadmin/add') || request()->is('subadmin/edit') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('admin.subadmin') }}" class="{{ request()->is('subadmin')? 'active' : ''}}">
                            <i class="bi bi-circle"></i><span>List</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.subadmin.add') }}" class="{{ request()->is('subadmin/add') || request()->is('subadmin/edit') ? 'active' : ''}}">
                            <i class="bi bi-circle"></i><span>Add</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        @endif

       
        <!-- End Dashboard Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="components-alerts.html">
                    <i class="bi bi-circle"></i><span>Alerts</span>
                    </a>
                </li>
                <li>
                    <a href="components-accordion.html">
                    <i class="bi bi-circle"></i><span>Accordion</span>
                    </a>
                </li>
                <li>
                    <a href="components-badges.html">
                    <i class="bi bi-circle"></i><span>Badges</span>
                    </a>
                </li>
                <li>
                    <a href="components-breadcrumbs.html">
                    <i class="bi bi-circle"></i><span>Breadcrumbs</span>
                    </a>
                </li>
                <li>
                    <a href="components-buttons.html">
                    <i class="bi bi-circle"></i><span>Buttons</span>
                    </a>
                </li>
                <li>
                    <a href="components-cards.html">
                    <i class="bi bi-circle"></i><span>Cards</span>
                    </a>
                </li>
                <li>
                    <a href="components-carousel.html">
                    <i class="bi bi-circle"></i><span>Carousel</span>
                    </a>
                </li>
                <li>
                    <a href="components-list-group.html">
                    <i class="bi bi-circle"></i><span>List group</span>
                    </a>
                </li>
                <li>
                    <a href="components-modal.html">
                    <i class="bi bi-circle"></i><span>Modal</span>
                    </a>
                </li>
                <li>
                    <a href="components-tabs.html">
                    <i class="bi bi-circle"></i><span>Tabs</span>
                    </a>
                </li>
                <li>
                    <a href="components-pagination.html">
                    <i class="bi bi-circle"></i><span>Pagination</span>
                    </a>
                </li>
                <li>
                    <a href="components-progress.html">
                    <i class="bi bi-circle"></i><span>Progress</span>
                    </a>
                </li>
                <li>
                    <a href="components-spinners.html">
                    <i class="bi bi-circle"></i><span>Spinners</span>
                    </a>
                </li>
                <li>
                    <a href="components-tooltips.html">
                    <i class="bi bi-circle"></i><span>Tooltips</span>
                    </a>
                </li>
            </ul>
        </li> -->
        <!-- End Components Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="forms-elements.html">
                    <i class="bi bi-circle"></i><span>Form Elements</span>
                    </a>
                </li>
                <li>
                    <a href="forms-layouts.html">
                    <i class="bi bi-circle"></i><span>Form Layouts</span>
                    </a>
                </li>
                <li>
                    <a href="forms-editors.html">
                    <i class="bi bi-circle"></i><span>Form Editors</span>
                    </a>
                </li>
                <li>
                    <a href="forms-validation.html">
                    <i class="bi bi-circle"></i><span>Form Validation</span>
                    </a>
                </li>
            </ul>
        </li> -->
        <!-- End Forms Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="tables-general.html">
                    <i class="bi bi-circle"></i><span>General Tables</span>
                    </a>
                </li>
                <li>
                    <a href="tables-data.html">
                    <i class="bi bi-circle"></i><span>Data Tables</span>
                    </a>
                </li>
            </ul>
        </li> -->
        <!-- End Tables Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="charts-chartjs.html">
                    <i class="bi bi-circle"></i><span>Chart.js</span>
                    </a>
                </li>
                <li>
                    <a href="charts-apexcharts.html">
                    <i class="bi bi-circle"></i><span>ApexCharts</span>
                    </a>
                </li>
                <li>
                    <a href="charts-echarts.html">
                    <i class="bi bi-circle"></i><span>ECharts</span>
                    </a>
                </li>
            </ul>
        </li> -->
        <!-- End Charts Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-gem"></i><span>Icons</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="icons-bootstrap.html">
                    <i class="bi bi-circle"></i><span>Bootstrap Icons</span>
                    </a>
                </li>
                <li>
                    <a href="icons-remix.html">
                    <i class="bi bi-circle"></i><span>Remix Icons</span>
                    </a>
                </li>
                <li>
                    <a href="icons-boxicons.html">
                    <i class="bi bi-circle"></i><span>Boxicons</span>
                    </a>
                </li>
            </ul>
        </li> -->
        <!-- End Icons Nav -->
        <!-- <li class="nav-heading">Pages</li> -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="users-profile.html">
            <i class="bi bi-person"></i>
            <span>Profile</span>
            </a>
        </li> -->
        <!-- End Profile Page Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-faq.html">
            <i class="bi bi-question-circle"></i>
            <span>F.A.Q</span>
            </a>
        </li> -->
        <!-- End F.A.Q Page Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-contact.html">
            <i class="bi bi-envelope"></i>
            <span>Contact</span>
            </a>
        </li> -->
        <!-- End Contact Page Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-register.html">
            <i class="bi bi-card-list"></i>
            <span>Register</span>
            </a>
        </li> -->
        <!-- End Register Page Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-login.html">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Login</span>
            </a>
        </li> -->
        <!-- End Login Page Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-error-404.html">
            <i class="bi bi-dash-circle"></i>
            <span>Error 404</span>
            </a>
        </li> -->
        <!-- End Error 404 Page Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-blank.html">
            <i class="bi bi-file-earmark"></i>
            <span>Blank</span>
            </a>
        </li> -->
        <!-- End Blank Page Nav -->
    </ul>
</aside>
<!-- End Sidebar-->