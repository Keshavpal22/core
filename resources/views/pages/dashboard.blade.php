@extends('layouts.main') 
@section('title', 'Dashboard')
@section('content')
    <!-- push external head elements to head -->
    @push('head')

        <link rel="stylesheet" href="{{ asset('plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/chartist/dist/chartist.min.css') }}">
    @endpush

    <div class="container-fluid">
    <div class="row">
        {{-- Your existing row content above or below --}}

        {{-- Horizontal Tabs navigation --}}
        <nav class="w-100">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="1.html" 
                       class="nav-link active">
                        Page 1
                    </a>
                </li>
                <li class="nav-item">
                    <a href="2.html" 
                       class="nav-link {{ request()->routeIs('dashboard.page2') ? 'active' : '' }}">
                        Page 2
                    </a>
                </li>
                <li class="nav-item">
                    <a href="3.html" 
                       class="nav-link {{ request()->routeIs('dashboard.page3') ? 'active' : '' }}">
                        Page 3
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Page content for the selected tab (below tabs) --}}
        <div class="tab-content w-100 p-3 border border-top-0">
            @yield('page-content')
        </div>

        {{-- Your existing content continues here --}}
    </div>
</div>
<!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
        <!-- <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script> -->
        <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

        <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>
       
        
        <script src="{{ asset('js/widget-statistic.js') }}"></script>
        <script src="{{ asset('js/widget-data.js') }}"></script>
        <script src="{{ asset('js/dashboard-charts.js') }}"></script>
        
    @endpush
@endsection