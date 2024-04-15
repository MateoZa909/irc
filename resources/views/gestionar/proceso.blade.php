<div class="modal fade" id="modal_proceso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #E22A3D">
                <h4 class="text-white">Procesos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="table-proceso" class="table table-bordered">
                                    <thead
                                        style="background-color: #E22A3D; color:#ffff; text-align: center !important;">
                                        <tr>
                                            <th scope="col">Tipo De Proceso</th>
                                            <th scope="col">Activo</th>
                                            <th scope="col">Mes</th>
                                            <th scope="col">Fecha De Cargue</th>
                                            <th scope="col">Fecha De Reporte</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color: #ffff; text-align: center;" name="tbody_modal_proceso">
                                        <tr>
                                            {{-- <td>{{$proceso->tpp_proceso}}</td>
                                        <td class="fw-bold">{{$proceso->activo}}</td>
                                        <td>{{$proceso->mes}}</td>
                                        <td>{{$proceso->fechacargue}}</td>
                                        <td>{{$proceso->fechareporte}}</td> --}}
                                        </tr>
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
