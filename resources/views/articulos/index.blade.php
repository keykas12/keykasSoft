@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Artículos</h1>
    <a href="{{ route('articulos.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i> Nuevo
    </a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success mt-3">{{ session('ok') }}</div>
  @endif

  <form class="row g-2 my-3" method="get" action="{{ route('articulos.index') }}">
    <div class="col-sm-8 col-md-6">
      <input type="text" name="q" class="form-control" placeholder="Buscar por código o nombre" value="{{ $q }}">
    </div>
    <div class="col-sm-4 col-md-2">
      <button class="btn btn-outline-secondary w-100">
        <i class="bi bi-search me-1"></i> Buscar
      </button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-sm table-hover align-middle">
      <thead>
        <tr>
          <th style="width: 80px;">ID</th>
          <th>Código</th>
          <th>Nombre</th>
          <th style="width: 110px;">Tipo</th>
          <th style="width: 120px;">Unidad (ID)</th>
          <th style="width: 120px;">Estado</th>
          <th style="width: 1%;"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($items as $a)
          <tr>
            <td>{{ $a->id_articulo }}</td>
            <td>{{ $a->codigo }}</td>
            <td>{{ $a->nombre }}</td>
            <td>{{ $a->tipo }}</td>
            <td>{{ $a->id_unidad }}</td>
            <td>
              @if($a->estado === 'A')
                <span class="badge bg-success">Activo</span>
              @else
                <span class="badge bg-secondary">Inactivo</span>
              @endif
            </td>
            <td>
              <a href="{{ route('articulos.edit', $a->id_articulo) }}" class="btn btn-sm btn-outline-primary">
                Editar
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted">Sin resultados</td>
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
