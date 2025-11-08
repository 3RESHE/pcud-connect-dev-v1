<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - PCU-DASMA Connect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
       <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#1E40AF",
                        secondary: "#3B82F6",
                        accent: "#F59E0B",
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    @include('components.staff.navbar')

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </div>

    <script src="{{ asset('js/staff/navigation.js') }}"></script>
</body>
</html>
