@extends('layouts.main')

@section('title')
    Centro de Ayuda
@endsection

@section('style')
@endsection



@section('content')
    <div class="row d-flex rounded">

        <div class="row  mr-3 ml-3 mb-3 ">
        {{-- - Comienzo Manuales --}}
        <div class="col-12">
            <div class="container m-2  rounded pb-3" style="padding-left: 300px; padding-right: 300px;">
                <h3 class="text-center">Manuales de Usuario</h3>
                <div class="row g-2 m-2 pt-2">
                    <div class="col-4">
                        <a class="text-center custom-link" href="{{ asset('files/manuales_usuarios/admin_irc.pdf') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                            </div>
                            <p>Manual <br>Administrador IRC</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <a class="text-center custom-link" href="{{ asset('files/manuales_usuarios/agentes.pdf') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                            </div>
                            <p>Manual <br>Agentes</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <a class="text-center custom-link" href="{{ asset('files/manuales_usuarios/supervisor.pdf') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                            </div>
                            <p>Manual <br>Supervisor</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- Fin Manuales --}}

        {{-- Comienzo Plantillas --}}
        <div class="col-6">
            <div class="container  m-2 py-3 rounded">
                <h3 class="text-center">Plantillas</h3>
                <div class="row m-2 pt-2">
                    <div class="col-3">
                        <a class="text-center custom-link" href="{{ asset('files/plantillas/IRINA20221102A1.xlsx') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-excel" style="font-size:20px"></i>
                            </div>
                            <p>Plantilla <br>Inasistidos</p>
                        </a>
                    </div>
                    <div class="col-3">
                        <a class="text-center custom-link" href="{{ asset('files/plantillas/IRSEG20221102A1.xlsx') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-excel" style="font-size:20px"></i>
                            </div>
                            <p>Plantilla <br>Segumientos</p>
                        </a>
                    </div>
                    <div class="col-3">
                        <a class="text-center custom-link" href="{{ asset('files/plantillas/IRREC20221102A1.xlsx') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-excel" style="font-size:20px"></i>
                            </div>
                            <p>Plantilla <br>Recordatorios</p>
                        </a>
                    </div>
                    <div class="col-3">
                        <a class="text-center custom-link" href="{{ asset('files/plantillas/IRHOS20221102A1.xlsx') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-excel" style="font-size:20px"></i>
                            </div>
                            <p>Plantilla <br>Hospitalizados</p>
                        </a>
                    </div>
                    <div class="col-3">
                        <a class="text-center custom-link" href="{{ asset('files/plantillas/IRBRI20221102A1.xlsx') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-excel" style="font-size:20px"></i>
                            </div>
                            <p>Plantilla <br>Brigadas</p>
                        </a>
                    </div>
                    <div class="col-3">
                        <a class="text-center custom-link" href="{{ asset('files/plantillas/IRREP20221102A1.xlsx') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-excel" style="font-size:20px"></i>
                            </div>
                            <p>Plantilla <br>Reporgramacion</p>
                        </a>
                    </div>
                    <div class="col-3">
                        <a class="text-center custom-link" href="{{ asset('files/plantillas/IRCAP20221102A1.xlsx') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-excel" style="font-size:20px"></i>
                            </div>
                            <p>Plantilla <br>Captacion</p>
                        </a>
                    </div>
                    <div class="col-3">
                        <a class="text-center custom-link" href="{{ asset('files/plantillas/IRPACIENTES.xlsx') }}" style="font-size: 10px;" target="_blank">
                            <div class="text-center">
                                <i class="fa-solid fa-file-excel" style="font-size:20px"></i>
                            </div>
                            <p>Plantilla <br>Pacientes</p>
                        </a>
                    </div>

                </div>
            </div>
        </div>
        {{-- fin Plantillas --}}

        <div class="col-6">
            <div class="row">
                <div class="col-12 py-3">
                    <h3 class="text-center">Manuales de Plantillas</h3>
                </div>
                <div class="col-3">
                    <a class="text-center custom-link" href="{{ asset('files/manuales_plantillas/ina_detalles.pdf') }}" style="font-size: 10px;" target="_blank">
                        <div class="text-center">
                            <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                        </div>
                        <p>Manual <br>Inasistidos</p>
                    </a>
                </div>
                <div class="col-3">
                    <a class="text-center custom-link" href="{{ asset('files/manuales_plantillas/seg_detalles.pdf') }}" style="font-size: 10px;" target="_blank">
                        <div class="text-center">
                            <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                        </div>
                        <p>Manual <br>Seguimiento</p>
                    </a>
                </div>
                <div class="col-3">
                    <a class="text-center custom-link" href="{{ asset('files/manuales_plantillas/rec_detalles.pdf') }}" style="font-size: 10px;" target="_blank">
                        <div class="text-center">
                            <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                        </div>
                        <p>Manual <br>Recordatorios</p>
                    </a>
                </div>
                <div class="col-3">
                    <a class="text-center custom-link" href="{{ asset('files/manuales_plantillas/hos_detalles.pdf') }}" style="font-size: 10px;" target="_blank">
                        <div class="text-center">
                            <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                        </div>
                        <p>Manual <br>Hospitalizados</p>
                    </a>
                </div>
                <div class="col-3">
                    <a class="text-center custom-link" href="{{ asset('files/manuales_plantillas/bri_detalles.pdf') }}" style="font-size: 10px;" target="_blank">
                        <div class="text-center">
                            <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                        </div>
                        <p>Manual <br>Brigadas</p>
                    </a>
                </div>
                <div class="col-3">
                    <a class="text-center custom-link" href="{{ asset('files/manuales_plantillas/rep_detalles.pdf') }}" style="font-size: 10px;" target="_blank">
                        <div class="text-center">
                            <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                        </div>
                        <p>Manual <br>Reprogramacion</p>
                    </a>
                </div>
                <div class="col-3">
                    <a class="text-center custom-link" href="{{ asset('files/manuales_plantillas/cap_detalles.pdf') }}" style="font-size: 10px;" target="_blank">
                        <div class="text-center">
                            <i class="fa-solid fa-file-pdf" style="font-size:20px"></i>
                        </div>
                        <p>Manual <br>Captacion</p>
                    </a>
                </div>
                <div class="col-3">

                </div>
            </div>

        </div>
    </div>
    </div>
@endsection




@section('script')
@endsection
