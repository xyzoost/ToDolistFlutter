<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --border-color: #e2e8f0;
            --success-color: #10b981;
        }

        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.5;
        }

        .bg-primary {
            background-color: var(--bg-primary);
        }

        .bg-secondary {
            background-color: var(--bg-secondary);
        }

        .border-light {
            border-color: var(--border-color);
        }

        .text-primary {
            color: var(--text-primary);
        }

        .text-secondary {
            color: var(--text-secondary);
        }

        .focus-primary:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .btn-primary {
            background-color: var(--primary-color);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        }

        .link-primary {
            color: var(--primary-color);
            transition: color 0.2s ease;
        }

        .link-primary:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .animate-fade {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="animate-fade">
        @yield('content')
    </div>

    <script>
        // Smooth transitions for page loads
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('[data-animate]');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('animate-fade');
                }, index * 100);
            });
        });
    </script>
</body>
</html>
