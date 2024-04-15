<div class="modal fade" id="modal_gestion" tabindex="-1" aria-labelledby="gestion-title" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #E22A3D">
                <h4 class="text-white">Gestionar paciente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="desactivar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container pl-4 pr-4">
                    {{-- gestion --}}

                    <div class="contanier-fluid">
                        <div class="row mb-4">
                            {{-- Gestion e informacion --}}
                            <div class="col-6 p-4">
                                {{-- Formulario --}}
                                <form method="POST" name="form-data" enctype="multipart/form-data" class="row mb-4">
                                    <input type="text" style="display: none;" id="tpp_id" name="tpp_id">
                                    <input type="text" style="display: none;" id="pro_id" name="pro_id">
                                    <input type="text" style="display: none;" id="pac_id" name="pac_id">
                                    <input type="text" style="display: none;" id="usu_id" name="usu_id"
                                        value="{{ Auth::user()->id }}">
                                    @csrf
                                    <div class="col-12 mb-3" style="padding-left:0px, max-width: 495PX !important;">
                                        <h6>Resultado</h6>
                                        <div class="select select_gestion  col-12  align-items-center">
                                            <select id="seleccion" name="tge_id" style="font-weight: bold;" required>
                                                <option selected value="" disabled>Selecionar Resultado</option>
                                                @foreach ($tipo_procesos as $tge)
                                                    <option value="{{ $tge->tge_id }}">{{ $tge->tge_nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3" style="padding-left:0px, max-width: 495PX !important;">
                                        <h6>Tipo paciente</h6>
                                        <div class="select select_gestion  col-12  align-items-center">
                                            <select id="seleccion" name="tpa_id" style="font-weight: bold;" required>
                                                <option selected value="" disabled>Selecionar</option>
                                                @foreach ($tipos_pacientes as $tpa)
                                                    <option value="{{ $tpa->tpa_id }}">{{ $tpa->tpa_nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3" style="padding-left:0px, max-width: 495PX !important;">
                                        <h6>Tipo de programa</h6>
                                        <div class="select select_gestion  col-12  align-items-center">
                                            <select id="seleccion" name="tpr_id" style="font-weight: bold;" required>
                                                <option selected value="" disabled>Selecionar Resultado</option>
                                                @foreach ($tipos_programas as $tpr)
                                                    <option value="{{ $tpr->tpr_id }}">{{ $tpr->tpr_nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="col-12 col_style" style="display: none;" name="div_input_datetime">
                                        <h6>Fecha de la Nueva Cita</h6>
                                        <input type="datetime-local" id="fecha_cita" name="fecha_cita">
                                    </div> --}}

                                    <div class="col-12 mb-3" style="padding-left:0px, max-width: 495PX !important;">
                                        <h6>Motivo Inasistencia</h6>
                                        <div class="select select_gestion  col-12  align-items-center">
                                            <select id="seleccion" name="motivo_inasistencia"
                                                style="font-weight: bold;">
                                                <option selected value="" disabled>Selecionar Resultado</option>
                                                @foreach ($tipos_inasistencias as $list)
                                                    <option value="{{ $list->tin_id }}">{{ $list->tin_nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3" style="padding-left:0px, max-width: 495PX !important;">
                                        <h6>Tipo de recordatorio</h6>
                                        <div class="select select_gestion  col-12  align-items-center">
                                            <select id="seleccion" name="tin_id" style="font-weight: bold;">
                                                <option selected value="" disabled>Selecionar Resultado</option>
                                                <option value="TELEFONICO">TELEFONICO</option>
                                                <option value="SMS">SMS</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 my-3">
                                        <h6>Comentario</h6>
                                        <textarea class="form-control" id="ges_comentario" name="ges_comentario" rows="3" style="max-width: 985px"
                                            placeholder="Escribe un comentario..."required></textarea>
                                    </div>
                                    <div class="col-6  text-left mt-2">
                                        <button type="button" class="btn  btn_ver_mas fixed-position"
                                            onclick="activar()">
                                            <p id="texto_ver" style="margin-bottom: 0px">Ver Historial</p>
                                        </button>
                                    </div>
                                    <div class="col-6  text-right mt-2">
                                        <button class="btn btn-primary" type="button" id="enviar_gestion">Enviar
                                            Gestion</button>
                                    </div>
                                </form>
                            </div>


                            {{-- Informacion del proceso --}}
                            <div class="col-6 p-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center">
                                            <h4 class="mb-3">Informacion de Paciente</h4>
                                        </div>
                                        <table class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <td class="text-center bold">Documento</td>
                                                    <td class="text-center bold">Nombre Completo</td>
                                                    <td class="text-center bold">Telefono</td>
                                                </tr>
                                            </thead>
                                            <tbody name="tbody_modal_info_personal">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12">
                                        <div class="text-center">
                                            <h4 class="mb-3"><span id="span_proceso"></span></h4>
                                        </div>
                                        <!-- Informacion info proceso -->
                                        <table class="table table-responsive" name="tbody_modal_info_proceso">
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- Historial gestion proceso -->
                    <div class="container-fluid" style="display: none" id="historial">
                        <div class="row mt-2 py-2">
                            <div class="col-12">
                                <div class="text-center">
                                    <h4> Historial de Gestiones</h4>
                                </div>
                                <div class="table-responsive">
                                    <table id="table-gestion" class="table table-bordered">
                                        <thead
                                            style="background-color: #E22A3D; color:#ffff; text-align: center !important;">
                                            <tr>
                                                <th scope="col">Fecha de gestion</th>
                                                <th scope="col">Agente</th>
                                                <th scope="col">Resultado</th>
                                                <th scope="col">Comentarios</th>
                                            </tr>
                                        </thead>
                                        <tbody style="background-color: #ffff; text-align: center;"
                                            name="tbody_modal_gestion">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
