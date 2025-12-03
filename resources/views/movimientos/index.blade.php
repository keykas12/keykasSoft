@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Movimientos de inventario</h1>

    <form method="get" class="row g-2 mb-3">
        <div class="col-sm-6 col-md-4">
            <input type="text" name="q" class="form-control"
                   placeholder="Buscar artículo o documento"
                   value="{{ $q }}">
        </div>
        <div class="col-sm-2">
            <button class="btn btn-outline-secondary w-100">
                <i class="bi bi-search"></i> Buscar
            </button>
        </div>
        <div class="col-sm-2">
            <a href="{{ route('movimientos.create') }}" class="btn btn-primary w-100">
                <i class="bi bi-plus-lg"></i> Nuevo
            </a>
        </div>
    </form>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Artículo</th>
                    <th>Bodega</th>
                    <th>Cantidad</th>
                    <th>Documento</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $m)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($m->fecha)->format('Y-m-d H:i') }}</td>
                    <td>{{ $m->tipo }}</td>
                    <td>{{ $m->art_codigo }} - {{ $m->art_nombre }}</td>
                    <td>{{ $m->bodega }}</td>
                    <td class="{{ $m->cantidad < 0 ? 'text-danger' : 'text-success' }}">
                        {{ $m->cantidad }}
                    </td>
                    <td>{{ $m->doc_ref }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Sin movimientos</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $items->links() }}
    </div>
</div>
@endsection
