@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Bienvenido</h1>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box-seam"></i> Artículos</h5>
                    <p class="card-text">Gestiona tu catálogo de artículos.</p>
                    <a href="{{ route('articulos.index') }}" class="btn btn-sm btn-primary">Ver artículos</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-arrow-left-right"></i> Movimientos</h5>
                    <p class="card-text">Registra entradas y salidas de inventario.</p>
                    <a href="{{ route('movimientos.index') }}" class="btn btn-sm btn-primary">Ver movimientos</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
