@extends('layouts.main')

@section('title')
    Excel-REP
@endsection

@section('style')
<style>
    ul.pagination{
        display: none;
    }
    div#table_cap_info{
        display: none;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-body">
                <a>Cantidad: <span id="a_cantidad">{{ $total }}</span></a>

                <button class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#segmentar">
                    <i class='fa-solid fa-person-circle-plus text-center' style='font-size: 20px;'></i></button>

                <input type="text" value="{{ $id }}" id="car_id" name="car_id" style="display: none;">

                <div class="d-flex flex-row ml-3 text-center">
                    <div class="flex-column">
                        <select onchange="filtro();" class="custom-select" id="dep_id" name="dep_id">
                            <option class="form-control" value="" selected>Departamentos
                            </option>
                            @foreach ($departamentos as $dep)
                                <option value="{{ $dep->dep_id }}">{{ $dep->dep_nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-column">
                        <select onchange="filtro();" class="custom-select" id="mun_id" name="mun_id">
                            <option class="form-control" value="" selected>Municipios
                            </option>
                            @foreach ($municipios as $mun)
                                <option value="{{ $mun->mun_id }}">{{ $mun->mun_nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-column">
                        <select onchange="filtro();" class="custom-select" id="pro_prioridad" name="pro_prioridad">
                            <option class="form-control" value="" selected>Prioridad
                            </option>
                            @foreach ($prioridades as $pri)
                                <option value="{{ $pri->pro_prioridad }}">{{ $pri->pro_prioridad }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-column">
                        <select onchange="filtro();" class="custom-select" id="rep_convenio" name="rep_convenio">
                            <option class="form-control" value="" selected>Convenios
                            </option>
                            @foreach ($convenios as $con)
                                <option value="{{ $con->convenio }}">{{ $con->convenio }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-column">
                        <select onchange="filtro();" class="custom-select" id="rep_especialidad" name="rep_especialidad">
                            <option class="form-control" value="" selected>Especialidad
                            </option>
                            @foreach ($especialidades as $esp)
                                <option value="{{ $esp->especialidad }}">{{ $esp->especialidad }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                @include('administrar_procesos.excel.modal')

            </div>
        </div>

        @include('layouts.msj')

        <div class="table-responsive">
            <table class="table" id="table_rep">
                <thead>
                    <tr>
                        <th scope="col">Marcador</th>
                        <th scope="col">Prioridad</th>
                        <th scope="col">Tipo Doc.</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Fecha cita</th>
                        <th scope="col">Especialidad</th>
                        <th scope="col">Medico</th>
                        <th scope="col">Convenio</th>
                    </tr>
                </thead>
                <tbody name="tbody_excel_rep">
                    @foreach ($procesos as $list)
                        <tr>
                            <td style="padding-top: 20px;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="marcador_{{ $list->pro_id }}"
                                    onchange="marca_gestion({{ $list->pro_id }}, {{ Auth::user()->id}})"
                                        @if ($list->pro_gestionado == 1) checked @endif>
                                    <label class="custom-control-label" for="marcador_{{ $list->pro_id }}" style=""></label>
                                    <input type="text" style="display: none" name="" id=""
                                        value="{{ $list->pro_gestionado }}">
                                </div>
                            </td>
                            <td>
                                @if ($list->pro_prioridad == 1)
                                    <!-- Si es prioridad 1 -->
                                    <span style="display: none;">1</span>
                                    <i class="fa-solid fa-circle circle-red" id="pri_red_{{ $list->pro_id }}"></i>
                                @endif
                                @if ($list->pro_prioridad == 2)
                                    <!-- Si es prioridad 2 -->
                                    <span style="display: none;">2</span>
                                    <i class="fa-solid fa-circle circle-yellow" id="pri_yellow_{{ $list->pro_id }}"></i>
                                @endif
                                @if ($list->pro_prioridad == 3)
                                    <!-- Si es prioridad 3 -->
                                    <span style="display: none;">3</span>
                                    <i class="fa-solid fa-circle circle-green" id="pri_green_{{ $list->pro_id }}"></i>
                                @endif
                            </td>
                            <td>{{ $list->tip_alias }}</td>
                            <td>{{ $list->pac_identificacion }}</td>
                            <td>{{ $list->pac_nombre_completo }}</td>
                            <td>{{ $list->dep_nombre }}</td>
                            <td>{{ $list->mun_nombre }}</td>
                            <td>{{ $list->rep_fecha_cita}}</td>
                            <td>{{ $list->rep_especialidad}}</td>
                            <td>{{ $list->rep_profesional}}</td>
                            <td>{{ $list->rep_convenio}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('js/excel/rep.js') }}"></script>

    {{-- <script>
        $(document).ready(function() {
            $('#modal_asignar_excel').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script> --}}
@endsection
