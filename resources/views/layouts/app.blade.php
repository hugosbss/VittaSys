<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'VittaSys' }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    },
                    colors: {
                        ink: '#1f2937',
                        brand: '#0ea5a4',
                        brandDark: '#0b7f7e',
                        bgSoft: '#f6f4ef',
                        surface: '#ffffff'
                    },
                    boxShadow: {
                        soft: '0 20px 40px rgba(15, 23, 42, 0.08)'
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[radial-gradient(1200px_600px_at_10%_0%,#fff6e6_0%,#f6f4ef_55%)] text-ink font-sans">
    @include('components.navbar')
    <main>
        @yield('content')
    </main>
</body>
</html>
