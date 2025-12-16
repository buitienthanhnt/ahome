<!--
=========================================================
* Material Dashboard 2 - v3.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/source/adminhtml/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/source/adminhtml/img/favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title')
    </title>

    @yield('meta')

    @include('adminhtml.ahome.layouts.components.headElements.css')

    @include('adminhtml.ahome.layouts.components.headElements.js')
</head>

<body>
    <x-adminhtml.layouts.components.message />
    @include('adminhtml.ahome.layouts.components.bodyElements.css')

    @include('adminhtml.ahome.layouts.components.bodyElements.sideBar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container-fluid">
            @yield('mainBody')
        </div>
    </main>
    @include('adminhtml.ahome.layouts.components.bodyElements.setting')

    @include('adminhtml.ahome.layouts.components.bodyElements.js')
</body>

</html>
