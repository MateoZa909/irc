@extends('layouts.main')

@section('title')
    Gestionar
@endsection

@section('style')
    <style>
        .label-error::after {
            content: "\f12a";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: red;
            margin-left: 5px;
        }
    </style>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-4">
                    <input type="text" class="form-control select-pro-ges" placeholder="Buscar..."
                        id="serch_paciente_procesos" name="serch_paciente_procesos">
                </div>
                <div class="col-4">
                    <select class="custom-select select-pro-ges" id="departamento_select">
                        <option class="form-control" value="" selected>Todos los departamentos
                        </option>
                        @foreach ($departamentos as $dep)
                            <option value="{{ $dep->dep_id }}">{{ $dep->dep_nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <select class="custom-select select-pro-ges" id="proceso_select">
                        <option class="form-control" value="" selected>Todos los procesos
                        </option>
                        @foreach ($tipos_procesos as $tpp)
                            <option value="{{ $tpp->tpp_id }}">{{ $tpp->tpp_nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <br>
        </div>
        <div class="col-12">
            @include('layouts.msj')
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background-color: #E22A3D; color:#ffff; text-align:center;">
                        <tr>
                            <th style="text-align: center;">Marcador</th>
                            <th style="text-align: center;">Prioridad</th>
                            <th style="text-align: center;">documento</th>
                            <th style="text-align: center;">Nombre</th>
                            <th style="text-align: center;">Teléfono</th>
                            <th style="text-align: center;">Proceso</th>
                            <th style="text-align: center;">Departamento</th>
                            <th style="text-align: center;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody name="procesos_gestion" style="background-color: #ffff; text-align: center;">
                        @foreach ($gestiones as $gestion)
                            <tr>
                                <td style="padding-top: 20px;">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input"
                                            id="marcador_{{ $gestion->pro_id }}"
                                            onchange="marca_gestion({{ $gestion->pro_id }}, {{ Auth::user()->id }})"
                                            @if ($gestion->pro_gestionado == 1) checked @endif>
                                        <label class="custom-control-label" for="marcador_{{ $gestion->pro_id }}"
                                            style=""></label>
                                        <input type="text" style="display: none" name="" id=""
                                            value="{{ $gestion->pro_gestionado }}">
                                    </div>
                                </td>
                                <!-- Esta es la variable -->
                                <td>
                                    @if ($gestion->pro_prioridad == 1)
                                        <!-- Si es prioridad 1 -->
                                        <span style="display: none;">1</span>
                                        <i class="fa-solid fa-circle circle-red" id="pri_red_{{ $gestion->pro_id }}"></i>
                                    @endif
                                    @if ($gestion->pro_prioridad == 2)
                                        <!-- Si es prioridad 2 -->
                                        <span style="display: none;">2</span>
                                        <i class="fa-solid fa-circle circle-yellow"
                                            id="pri_yellow_{{ $gestion->pro_id }}"></i>
                                    @endif
                                    @if ($gestion->pro_prioridad == 3)
                                        <!-- Si es prioridad 3 -->
                                        <span style="display: none;">3</span>
                                        <i class="fa-solid fa-circle circle-green"
                                            id="pri_green_{{ $gestion->pro_id }}"></i>
                                    @endif
                                </td>
                                <td>{{ $gestion->pac_identificacion }}</td>
                                <td>{{ $gestion->pac_primer_nombre }} {{ $gestion->pac_segundo_nombre }}
                                    {{ $gestion->pac_primer_apellido }} {{ $gestion->pac_segundo_apellido }}</td>
                                <td>{{ $gestion->pac_telefono }}</td>
                                <td>{{ $gestion->tpp_nombre }}</td>
                                <td>{{ $gestion->dep_nombre }}</td>
                                <td>
                                    <div class="d-flex aling-items-center">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#modal_proceso" onclick="modal_proceso({{ $gestion->pac_id }});">
                                            Proceso
                                        </button>
                                        <button type="button" class="btn btn-primary mx-1" data-toggle="modal"
                                            data-target="#modal_perfil" onclick="modal_perfil({{ $gestion->pac_id }});">
                                            Perfil
                                        </button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#modal_gestion"
                                            onclick="modal_gestion({{ $gestion->pro_id }}, {{ $gestion->tpp_id }}, {{ $gestion->pac_id }});">
                                            Gestion
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('gestionar.proceso')

    @include('gestionar.perfil')

    @include('gestionar.gestion')
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/funcionalidades/gestion_filtros.js') }}"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
@endsection
