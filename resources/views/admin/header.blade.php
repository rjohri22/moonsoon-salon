<!DOCTYPE html>
<html lang="en">

<head>
    <title>Monsoon App</title>
    <link rel="icon" type="image/png" href="{{ asset('admin_asset/images/m.png') }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" type="text/css" href="{{ asset('/admin_asset/css/adminx.css') }}" media="screen" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

    <!--
      # Optional Resources
      Feel free to delete these if you don't need them in your project
    -->

    <style>
        .required_symbol {
            color: red;
        }

        .error {
            color: red;
            font-size: small;
        }

        .admin-space {
            margin-top: 8px;
            position: absolute;
        }

    </style>
</head>

<body>
    <div class="adminx-container">
        <nav class="navbar navbar-expand justify-content-between fixed-top">
            <a class="navbar-brand mb-0 h1 d-none d-md-block" href="{{ url('admin/dashboard') }}">
                <img src="{{ asset('admin_asset/images/logo.jpeg') }}"
                    class="navbar-brand-image d-inline-block align-top mr-2" alt="">
                <span class="admin-space"> </span>
            </a>

            <form class="form-inline form-quicksearch d-none d-md-block mx-auto">
            </form>

            <div class="d-flex flex-1 d-block d-md-none">
                <a href="#" class="sidebar-toggle ml-3">
                    <i data-feather="menu"></i>
                </a>
            </div>

            <ul class="navbar-nav d-flex justify-content-end mr-2">
                <!-- Notificatoins -->
                <li class="nav-item dropdown d-flex align-items-center mr-2">
                    <a class="nav-link nav-link-notifications" id="dropdownNotifications" data-toggle="dropdown"
                        href="#">
                        <i class="oi oi-bell display-inline-block align-middle"></i>
                        <span class="nav-link-notification-number"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-notifications"
                        aria-labelledby="dropdownNotifications">
                        <div class="notifications-header d-flex justify-content-between align-items-center">
                            <span class="notifications-header-title">
                                Notifications
                            </span>
                            <a href="#" class="d-flex"><small>Mark all as read</small></a>
                        </div>

                        <div class="notifications-footer text-center">
                            <a href="#"><small>View all notifications</small></a>
                        </div>
                    </div>
                </li>
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link avatar-with-name" id="navbarDropdownMenuLink" data-toggle="dropdown" href="#">
                        <img src="https://img.icons8.com/external-sbts2018-lineal-color-sbts2018/50/000000/external-support-customer-support-sbts2018-lineal-color-sbts2018-1.png"
                            class="d-inline-block align-top" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ url('/admin/profile') }}"><i
                                class="fa fa-user fa-sm fa-fw mr-2"></i>My Profile</a>
                        <a class="dropdown-item" href="{{ url('/admin/password-update') }}"><i
                                class="fa fa-user fa-sm fa-fw mr-2"></i>Change Password</a>
                        <!-- <a class="dropdown-item" href="#">Settings</a> -->
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
         document.getElementById('logout-form').submit();" class="dropdown-item text-danger"><i
                                class="md md-settings-power mr-2"></i> {{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <!-- <a class="dropdown-item text-danger" href="#">Sign out</a> -->
                    </div>
                </li>
            </ul>
        </nav>

        <!-- expand-hover push -->
        <!-- Sidebar -->
        <div class="adminx-sidebar expand-hover push">
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/dashboard') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/dashboard') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-home fa-lg" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Dashboard
                        </span>
                        <span class="sidebar-nav-end">

                        </span>
                    </a>
                </li>

                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/brands') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/brands') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Brands

                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/branches') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/branches') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list"></i>
                        </span>
                        <span class="sidebar-nav-name">
                        Branchs

                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li>

                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/categories') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/categories') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Categories
                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li>

                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/sub-categories') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/sub-categories') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Sub-Categories
                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li>

                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/product-categories') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/product-categories') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Product-Categories
                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li>

                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/items') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/items') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Items
                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/services') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/services') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Service
                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/packages') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/packages') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Packages
                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li>

                {{-- <li class="sidebar-nav-item">
                    <a href="{{ url('/admin/orders') }}"
                        class="sidebar-nav-link @if (Request::path() == 'admin/orders') {{ 'active' }} @endif">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Orders
                        </span>
                        <span class="sidebar-nav-end">
                            <span class="badge badge-info"></span>
                        </span>
                    </a>
                </li> --}}

                <li class="sidebar-nav-item">
                    <a class="
                        sidebar-nav-link collapsed
                        @if (Request::path() == 'admin/cities') {{ 'active' }} @endif
                        @if (Request::path() == 'admin/route-settings') {{ 'active' }} @endif
                        @if (Request::path() == 'admin/upi-types') {{ 'active' }} @endif
                    "
                        data-toggle="collapse" href="#navTables2" aria-expanded="false" aria-controls="navTables">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-cart-arrow-down fa-lg" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Orders
                        </span>
                        <span class="sidebar-nav-end">
                            <i data-feather="chevron-right" class="nav-collapse-icon"></i>
                        </span>
                    </a>

                    <ul class="sidebar-sub-nav collapse" id="navTables2">
                        <li class="sidebar-nav-item">
                            <a href="{{ url('/admin/orders') }}"
                                class="sidebar-nav-link @if (Request::path() == 'admin/orders') {{ 'active' }} @endif">
                                <span class="sidebar-nav-abbr">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                </span>
                                <span class="sidebar-nav-name">
                                    Product Orders
                                </span>
                            </a>
                        </li>

                        <li class="sidebar-nav-item">
                            <a href="{{ url('/admin/service-orders') }}"
                                class="sidebar-nav-link @if (Request::path() == 'admin/service-orders') {{ 'active' }} @endif">
                                <span class="sidebar-nav-abbr">
                                    <i class="fa fa-paint-brush" aria-hidden="true"></i>
                                </span>
                                <span class="sidebar-nav-name">
                                    Service Orders
                                </span>
                            </a>
                        </li>



                    </ul>
                </li>

                <li class="sidebar-nav-item">
                    <a class="
                        sidebar-nav-link collapsed
                        @if (Request::path() == 'admin/cities') {{ 'active' }} @endif
                        @if (Request::path() == 'admin/route-settings') {{ 'active' }} @endif
                        @if (Request::path() == 'admin/upi-types') {{ 'active' }} @endif
                    "
                        data-toggle="collapse" href="#navTables1" aria-expanded="false" aria-controls="navTables">
                        <span class="sidebar-nav-icon">
                            <i class="fa fa-cogs fa-lg" aria-hidden="true"></i>
                        </span>
                        <span class="sidebar-nav-name">
                            Settings
                        </span>
                        <span class="sidebar-nav-end">
                            <i data-feather="chevron-right" class="nav-collapse-icon"></i>
                        </span>
                    </a>

                    <ul class="sidebar-sub-nav collapse" id="navTables1">
                        <li class="sidebar-nav-item">
                            <a href="{{ url('/admin/offers') }}"
                                class="sidebar-nav-link @if (Request::path() == 'admin/offers') {{ 'active' }} @endif">
                                <span class="sidebar-nav-abbr">
                                    <i class="fa fa-map" aria-hidden="true"></i>
                                </span>
                                <span class="sidebar-nav-name">
                                    Offers
                                </span>
                            </a>
                        </li>

                        <li class="sidebar-nav-item">
                            <a href="{{ url('/admin/payment-methods') }}"
                                class="sidebar-nav-link @if (Request::path() == 'admin/payment-methods') {{ 'active' }} @endif">
                                <span class="sidebar-nav-abbr">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                </span>
                                <span class="sidebar-nav-name">
                                    Payment Methods
                                </span>
                            </a>
                        </li>

                        <li class="sidebar-nav-item">
                            <a href="{{ url('/admin/shop-settings') }}"
                                class="sidebar-nav-link @if (Request::path() == 'admin/payment-methods') {{ 'active' }} @endif">
                                <span class="sidebar-nav-abbr">
                                    <i class="fa fa-gear" aria-hidden="true"></i>
                                </span>
                                <span class="sidebar-nav-name">
                                    Shop Settings
                                </span>
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="{{ url('/admin/delivery-regions') }}"
                                class="sidebar-nav-link @if (Request::path() == 'admin/payment-methods') {{ 'active' }} @endif">
                                <span class="sidebar-nav-abbr">
                                    <i class="fa fa-gear" aria-hidden="true"></i>
                                </span>
                                <span class="sidebar-nav-name">
                                    Delivery Region Settings
                                </span>
                            </a>
                        </li>

                    </ul>
                </li>
        </div><!-- Sidebar End -->
