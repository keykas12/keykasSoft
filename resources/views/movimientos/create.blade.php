@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Registrar movimiento</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <b>Corrige los siguientes errores:</b>
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="post" action="{{ route('movimientos.store') }}">
        @csrf

        <div class="row g-3">

            {{-- Tipo de movimiento --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Tipo de movimiento</label>
                <select name="tipo" id="tipoSelect" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    @foreach ($tipos as $k => $v)
                        <option value="{{ $k }}" @selected(old('tipo')==$k)>{{ $v }}</option>
                    @endforeach
                </select>
                @error('tipo') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Cantidad --}}
            <div class="col-md-2">
                <label class="form-label fw-bold">Cantidad</label>
                <input type="number" class="form-control" name="cantidad" min="1"
                       value="{{ old('cantidad') }}" required>
                @error('cantidad') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Documento --}}
            <div class="col-md-3">
                <label class="form-label fw-bold">Documento ref.</label>
                <input type="text" class="form-control" name="doc_ref" maxlength="40"
                       value="{{ old('doc_ref') }}">
                @error('doc_ref') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Observación --}}
            <div class="col-md-12">
                <label class="form-label fw-bold">Observación</label>
                <textarea class="form-control" name="observacion" rows="2">{{ old('observacion') }}</textarea>
            </div>

            {{-- ============================================= --}}
            {{-- SECCIÓN MATERIA PRIMA (MP) --}}
            {{-- ============================================= --}}
            <div class="col-12 mt-4 mp-section">
                <h5 class="fw-bold">Materia prima (MP)</h5>
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Bodega MP</label>
                        <select class="form-select" name="id_bodega_mp">
                            <option value="">--</option>
                            @foreach ($bodegasMp as $b)
                                <option value="{{ $b->id_bodega }}"
                                    @selected(old('id_bodega_mp') == $b->id_bodega)>
                                    {{ $b->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_bodega_mp') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Artículo MP</label>
                        <select class="form-select" name="id_articulo_mp">
                            <option value="">--</option>
                            @foreach ($articulosMp as $a)
                                <option value="{{ $a->id_articulo }}"
                                    @selected(old('id_articulo_mp') == $a->id_articulo)>
                                    {{ $a->codigo }} - {{ $a->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_articulo_mp') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    <div class="col-12">
                         <small id="stockMpLabel" class="text-muted"></small>
                        </div>

                </div>
            </div>

            {{-- ============================================= --}}
            {{-- SECCIÓN PRODUCTO TERMINADO (PT) --}}
            {{-- ============================================= --}}
            <div class="col-12 mt-4 pt-section">
                <h5 class="fw-bold">Producto terminado (PT)</h5>
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Bodega PT</label>
                        <select class="form-select" name="id_bodega_pt">
                            <option value="">--</option>
                            @foreach ($bodegasPt as $b)
                                <option value="{{ $b->id_bodega }}"
                                    @selected(old('id_bodega_pt') == $b->id_bodega)>
                                    {{ $b->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_bodega_pt') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Artículo PT</label>
                        <select class="form-select" name="id_articulo_pt">
                            <option value="">--</option>
                            @foreach ($articulosPt as $a)
                                <option value="{{ $a->id_articulo }}"
                                    @selected(old('id_articulo_pt') == $a->id_articulo)>
                                    {{ $a->codigo }} - {{ $a->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_articulo_pt') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
<div class="col-12">
    <small id="stockPtLabel" class="text-muted"></small>
</div>

                    {{-- VARIANTE PT --}}
                    <div class="col-md-4">
                        <label class="form-label">Variante PT</label>
                        <select class="form-select" name="id_pt_var" id="varianteSelect">
                            <option value="">-- Seleccione una variante --</option>
                        </select>
                        @error('id_pt_var') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>

        </div>

        <div class="mt-4">
            <button class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Guardar movimiento
            </button>
            <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection


@push('scripts')
@push('scripts')
<script>
(function(){

    const tipoSelect        = document.getElementById('tipoSelect');
    const mpSection         = document.querySelector('.mp-section');
    const ptSection         = document.querySelector('.pt-section');

    const bodegaMpSelect    = document.querySelector('[name="id_bodega_mp"]');
    const articuloMpSelect  = document.querySelector('[name="id_articulo_mp"]');

    const bodegaPtSelect    = document.querySelector('[name="id_bodega_pt"]');
    const articuloPtSelect  = document.querySelector('[name="id_articulo_pt"]');
    const varianteSelect    = document.getElementById('varianteSelect');

    const stockMpLabel      = document.getElementById('stockMpLabel');
    const stockPtLabel      = document.getElementById('stockPtLabel');

    // Valor anterior de variante si hubo error de validación
    const oldVariante = @json(old('id_pt_var'));

    // ==========================
    // Mostrar / ocultar secciones
    // ==========================
    function updateSections() {
        const t = tipoSelect.value;

        const esMP = (t === 'COMPRA_MP' || t === 'CONSUMO_MP');
        const esPT = (t === 'PRODUCCION_PT' || t === 'VENTA_PT' || t === 'AJUSTE_ENTRADA' || t === 'AJUSTE_SALIDA');

        mpSection.style.display = esMP ? '' : 'none';
        ptSection.style.display = esPT ? '' : 'none';
    }

    tipoSelect.addEventListener('change', updateSections);
    updateSections();

    // ==========================
    // Cargar VARIANTES PT por AJAX
    // ==========================
    function cargarVariantes(artId, seleccionarId = null) {
        if (!artId) {
            varianteSelect.innerHTML = '<option value="">-- Seleccione una variante --</option>';
            return;
        }

        varianteSelect.innerHTML = '<option value="">Cargando...</option>';

        fetch(`/inventario/pt-variantes/${artId}`)
            .then(res => res.json())
            .then(data => {
                varianteSelect.innerHTML = '<option value="">-- Seleccione una variante --</option>';
                data.forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v.id_pt_var;
                    opt.textContent = v.codigo_variante;
                    if (seleccionarId && String(seleccionarId) === String(v.id_pt_var)) {
                        opt.selected = true;
                    }
                    varianteSelect.appendChild(opt);
                });
            })
            .catch(() => {
                varianteSelect.innerHTML = '<option value="">Error cargando variantes</option>';
            });
    }

    // ==========================
    // API de STOCK (MP y PT)
    // ==========================
    function cargarStock(artId, bodId, labelEl) {
        if (!labelEl) return;

        if (!artId || !bodId) {
            labelEl.textContent = '';
            return;
        }

        labelEl.textContent = 'Consultando stock...';

        fetch(`/inventario/stock?articulo=${artId}&bodega=${bodId}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    labelEl.textContent = 'Error al consultar stock';
                } else {
                    labelEl.textContent = 'Stock actual: ' + data.stock;
                }
            })
            .catch(() => {
                labelEl.textContent = 'Error al consultar stock';
            });
    }

    function actualizarStockMp() {
        if (articuloMpSelect && bodegaMpSelect) {
            cargarStock(articuloMpSelect.value, bodegaMpSelect.value, stockMpLabel);
        }
    }

    function actualizarStockPt() {
        if (articuloPtSelect && bodegaPtSelect) {
            cargarStock(articuloPtSelect.value, bodegaPtSelect.value, stockPtLabel);
        }
    }

    // Eventos MP
    if (articuloMpSelect && bodegaMpSelect) {
        articuloMpSelect.addEventListener('change', actualizarStockMp);
        bodegaMpSelect.addEventListener('change', actualizarStockMp);
        // Si hay valores viejos, mostramos stock al cargar
        actualizarStockMp();
    }

    // Eventos PT (artículo + variantes + stock)
    if (articuloPtSelect) {
        articuloPtSelect.addEventListener('change', function () {
            cargarVariantes(this.value, null);
            actualizarStockPt();
        });

        if (articuloPtSelect.value) {
            cargarVariantes(articuloPtSelect.value, oldVariante || null);
        }
    }

    if (bodegaPtSelect) {
        bodegaPtSelect.addEventListener('change', actualizarStockPt);
        actualizarStockPt();
    }

})();
</script>
@endpush

@endpush
