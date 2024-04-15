@extends('layouts.main')

@section('title')
    Administrar Procesos
@endsection
@section('style')
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" />
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="col-6">
                    <div class="form-group">
                        <label for="label_search">Buscar:</label>
                        <input type="text" class="form-control" id="search">
                    </div>
                </div>
                <br>
                <div class="col-12">
                    @include('layouts.msj')
                    <div class="table-responsive">
                        <table id="table1" class="table table-bordered">
                            <thead style="background-color: #E22A3D; color:#ffff; text-align: center !important;">
                                <tr>
                                    <th>Fecha de cargue</th>
                                    <th>Mes</th>
                                    <th>Fecha de Reporte</th>
                                    <th>Tipo de Proceso</th>
                                    <th>Activo</th>
                                    <th>Asignar Agente</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #ffff; text-align: center;" id="registros" name="registros">
                                @foreach ($cargues as $list)
                                    <tr>
                                        <td>{{ $list->car_fecha_cargue }}</td>
                                        <td>{{ $list->car_mes }}</td>
                                        <td>{{ $list->car_fecha_reporte }}</td>
                                        <td> {{ $list->tpp_nombre }}</td>
                                        <td style="padding-top: 20px;">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="estado_{{ $list->car_id }}" onchange="cambio({{ $list->car_id }})"
                                                    @if ($list->car_activo == 'SI') checked @endif>
                                                <label class="custom-control-label" for="estado_{{ $list->car_id }}"
                                                    style=""></label>
                                                <input type="text" style="display: none" name="" id=""
                                                    value="{{ $list->car_id }}">
                                            </div>
                                        </td>
                                        <td>
                                            @switch($list->tpp_id)
                                                @case('1')
                                                    <a class="btn btn-primary" href="{{ route('proceso.e.ina', $list->car_id) }}">
                                                        <i class="far fa-eye"></i></a>
                                                @break

                                                @case('2')
                                                    <a class="btn btn-primary" href="{{ route('proceso.e.seg', $list->car_id) }}">
                                                        <i class="far fa-eye"></i></a>
                                                @break

                                                @case('3')
                                                    <a class="btn btn-primary" href="{{ route('proceso.e.rec', $list->car_id) }}">
                                                        <i class="far fa-eye"></i></a>
                                                @break

                                                @case('4')
                                                    <a class="btn btn-primary" href="{{ route('proceso.e.hos', $list->car_id) }}">
                                                        <i class="far fa-eye"></i></a>
                                                @break

                                                @case('5')
                                                    <a class="btn btn-primary" href="{{ route('proceso.e.bri', $list->car_id) }}">
                                                        <i class="far fa-eye"></i></a>
                                                @break

                                                @case('6')
                                                    <a class="btn btn-primary" href="{{ route('proceso.e.rep', $list->car_id) }}">
                                                        <i class="far fa-eye"></i></a>
                                                @break

                                                @case('7')
                                                    <a class="btn btn-primary" href="{{ route('proceso.e.cap', $list->car_id) }}">
                                                        <i class="far fa-eye"></i></a>
                                                @break

                                                @default
                                            @endswitch
                                            {{-- <button type='button' class='btn btn-primary' data-toggle='modal'
                                                    data-target='#modal_{{ $list->car_id }}' id='btn_asignar'>
                                                    <i class='fa-solid fa-person-circle-plus text-center'
                                                        style='font-size: 20px;'></i>
                                                </button> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('administrar_procesos.modal_asignar')



    {{-- <table class="table table-primary">
        <thead class="thead-primary">
            <tr>
                <th scope="col">Fecha de cargue</th>
                <th scope="col">Mes</th>
                <th scope="col">Fecha de Reporte</th>
                <th scope="col">Tipo de Proceso</th>
                <th scope="col">Activo</th>
                <th scope="col">Asignar Agente</th>
            </tr>
        </thead>
        <tbody style="background-color: #ffff">
            <td>20/02/2022</td>
            <td>Febrero</td>
            <td>24/02/2022</td>
            <td>Hospitalizados</td>
            <td>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1">
                    <label class="custom-control-label" for="customSwitch1" style=""></label>
                </div>
            </td>
            <td>
                @include('administrar_procesos.modal_asignar')
            </td>
        </tbody>
    </table>
    --}}
@endsection

@section('script')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('js/funcionalidades/procesos.js') }}"></script>
@endsection
