@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-3">Editar artículo</h1>

  @if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <strong>Revisa los campos:</strong>
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('articulos.update', $articulo->id_articulo) }}">
    @csrf
    @method('PUT')

    <div class="row g-3">
      <div class="col-md-2">
        <label class="form-label fw-semibold">Tipo</label>
        <select name="tipo" class="form-select" id="tipoSelect" required>
          <option value="">-- Seleccione --</option>
          <option value="MP" @selected(old('tipo', $articulo->tipo)==='MP')>Materia Prima</option>
          <option value="PT" @selected(old('tipo', $articulo->tipo)==='PT')>Producto Terminado</option>
        </select>
        @error('tipo') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-3">
        <label class="form-label fw-semibold">Código</label>
        <input type="text" name="codigo" class="form-control" maxlength="50"
               value="{{ old('codigo', $articulo->codigo) }}" required>
        @error('codigo') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-7">
        <label class="form-label fw-semibold">Nombre</label>
        <input type="text" name="nombre" class="form-control" maxlength="150"
               value="{{ old('nombre', $articulo->nombre) }}" required>
        @error('nombre') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-3">
        <label class="form-label fw-semibold">Unidad</label>
        <select name="id_unidad" class="form-select" required>
          <option value="">-- Seleccione --</option>
          @foreach ($unidades as $u)
            <option value="{{ $u->id_unidad }}" @selected(old('id_unidad', $articulo->id_unidad)==$u->id_unidad)>
              {{ $u->nombre }}{{ isset($u->codigo) ? ' ('.$u->codigo.')' : '' }}
            </option>
          @endforeach
        </select>
        @error('id_unidad') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-3">
        <label class="form-label fw-semibold">Marca</label>
        <select name="id_marca" class="form-select">
          <option value="">-- Ninguna --</option>
          @foreach ($marcas as $m)
            <option value="{{ $m->id_marca }}" @selected(old('id_marca', $articulo->id_marca)==$m->id_marca)>
              {{ $m->nombre }}
            </option>
          @endforeach
        </select>
        @error('id_marca') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      {{-- Solo MP --}}
      <div class="col-md-3 tipo-mp">
        <label class="form-label fw-semibold">Tipo de MP</label>
        <select name="id_mp_tipo" class="form-select">
          <option value="">-- Seleccione --</option>
          @foreach ($mpTipos as $t)
            <option value="{{ $t->id_mp_tipo }}" @selected(old('id_mp_tipo', $articulo->id_mp_tipo)==$t->id_mp_tipo)>
              {{ $t->nombre }}
            </option>
          @endforeach
        </select>
        @error('id_mp_tipo') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      {{-- Solo PT --}}
      <div class="col-md-3 tipo-pt">
        <label class="form-label fw-semibold">Categoría PT</label>
        <select name="id_categoria_pt" class="form-select">
          <option value="">-- Seleccione --</option>
          @foreach ($categorias as $c)
            <option value="{{ $c->id_categoria_pt }}" @selected(old('id_categoria_pt', $articulo->id_categoria_pt)==$c->id_categoria_pt)>
              {{ $c->nombre }}
            </option>
          @endforeach
        </select>
        @error('id_categoria_pt') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-3 tipo-pt">
        <label class="form-label fw-semibold">Subcategoría</label>
        <select name="id_subcategoria_pt" class="form-select">
          <option value="">-- (Opcional) --</option>
          @foreach ($subcategorias as $s)
            <option value="{{ $s->id_subcategoria_pt }}" @selected(old('id_subcategoria_pt', $articulo->id_subcategoria_pt)==$s->id_subcategoria_pt)>
              {{ $s->nombre }}
            </option>
          @endforeach
        </select>
        @error('id_subcategoria_pt') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-3">
        <label class="form-label fw-semibold">Estado</label>
        <select name="estado" class="form-select" required>
          <option value="A" @selected(old('estado', $articulo->estado)==='A')>Activo</option>
          <option value="I" @selected(old('estado', $articulo->estado)==='I')>Inactivo</option>
        </select>
        @error('estado') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mt-4">
      <button class="btn btn-primary">
        <i class="bi bi-save me-1"></i> Actualizar
      </button>
      <a href="{{ route('articulos.index') }}" class="btn btn-secondary">
        Volver
      </a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const tipoSel = document.getElementById('tipoSelect');
  if (!tipoSel) return;
  const toggle = () => {
    const isMP = tipoSel.value === 'MP';
    document.querySelectorAll('.tipo-mp').forEach(e => e.style.display = isMP ? '' : 'none');
    document.querySelectorAll('.tipo-pt').forEach(e => e.style.display = !isMP ? '' : 'none');
  };
  tipoSel.addEventListener('change', toggle);
  toggle();
})();
</script>
@endpush
