<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zapatería - Panel</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Estilo del panel --}}
    <style>
        body {
            background: #f3f4f6;
        }
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1e293b;
        }
        .sidebar a {
            color: #e2e8f0;
            padding: 12px 18px;
            display: block;
            text-decoration: none;
            font-size: 15px;
        }
        .sidebar a:hover {
            background: #334155;
            color: #fff;
        }
        .topbar {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="d-flex">

        {{-- ====================== --}}
        {{--      MENÚ LATERAL      --}}
        {{-- ====================== --}}
        <div class="sidebar">
            <h5 class="text-center text-light py-3 mb-3 border-bottom border-secondary">
                <i class="bi bi-shop"></i> Zapatería
            </h5>

            <a href="{{ url('/') }}">
                <i class="bi bi-house"></i> Inicio
            </a>

            <a href="{{ route('articulos.index') }}">
                <i class="bi bi-box-seam"></i> Artículos
            </a>

            <a href="{{ route('movimientos.index') }}">
                <i class="bi bi-arrow-left-right"></i> Movimientos
            </a>

            <a href="#">
                <i class="bi bi-archive"></i> Inventario
            </a>

            <a href="#">
                <i class="bi bi-gear"></i> Configuración
            </a>
        </div>

        {{-- ====================== --}}
        {{--      CONTENIDO         --}}
        {{-- ====================== --}}
        <div class="flex-grow-1">

            {{-- BARRA SUPERIOR --}}
            <nav class="topbar d-flex align-items-center px-3">
                <span class="fw-bold">Panel Principal</span>
            </nav>

            {{-- VISTA ESPECÍFICA --}}
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
