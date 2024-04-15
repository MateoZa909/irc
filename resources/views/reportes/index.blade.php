@extends('layouts.main')

@section('title')
    Reportes
@endsection


@section('content')
    <div class="row">

        @include('layouts.msj')

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-rep-general-tab" data-bs-toggle="tab" data-bs-target="#nav-rep-generales" type="button"
                    role="tab" aria-controls="nav-home" aria-selected="true" style="color: red;">REPORTE GENERAL</button>
                <button class="nav-link" id="nav-rep-personalizado-tab" data-bs-toggle="tab" data-bs-target="#nav-rep-personalizados"
                    type="button" role="tab" aria-controls="nav-profile" aria-selected="false"
                    style="color: red;">REPORTE PERSONALIZADO</button>
                <button class="nav-link" id="nav-adm-cargue-tab" data-bs-toggle="tab" data-bs-target="#nav-Administrar-cargues"
                    type="button" role="tab" aria-controls="nav-profile" aria-selected="false"
                    style="color: red;">ADMINISTRAR CARGUES</button>
                <button class="nav-link" id="nav-rep-agente-tab" data-bs-toggle="tab" data-bs-target="#nav-rep-agente"
                    type="button" role="tab" aria-controls="nav-profile" aria-selected="false"
                    style="color: red;">REPORTE POR AGENTE</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-rep-generales" role="tabpanel" aria-labelledby="nav-rep-general-tab">
                {{-- REPORTE GENERAL --}}
                @include('reportes.rep_generales')
                {{-- FIN REPORTE GENERAL --}}
            </div>
            <div class="tab-pane fade" id="nav-rep-personalizados" role="tabpanel" aria-labelledby="nav-rep-personalizado-tab">
                {{-- REPORTE PERSONALIZADO --}}
                @include('reportes.rep_personalizados')
                {{-- FIN REPORTE PERSONALIZADO --}}
            </div>
            <div class="tab-pane fade" id="nav-Administrar-cargues" role="tabpanel" aria-labelledby="nav-adm-cargue-tab">
                {{-- REPORTE PERSONALIZADO --}}
                @include('reportes.administar_carg')
                {{-- FIN REPORTE PERSONALIZADO --}}
            </div>
            <div class="tab-pane fade" id="nav-rep-agente" role="tabpanel" aria-labelledby="nav-rep-agente-tab">
                {{-- REPORTE PERSONALIZADO --}}
                @include('reportes.rep_agente')
                {{-- FIN REPORTE PERSONALIZADO --}}
            </div>
        </div>

    </div>
@endsection
