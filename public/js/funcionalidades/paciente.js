/* EVENTO DEL SEARCH */
serch_paciente.oninput = function() {

    var listado = $("[name=tbody_pac]");
    listado.empty();
    listado.append(
        '<tr><td colspan="7" >Buscando...</td></tr>'
    );

    data = {
        'busqueda': serch_paciente.value
    }

    $.ajax({
        url: '/search/pac',
        type: 'GET',
        dataType: 'json',
        data: data,
        beforeSend: function () {
            console.log('enviada');
        },
        complete: function () {
            console.log('completada');
        },
        success: function (response) {
            console.log(response);
            var resp = response;
            var data = resp.data;
            listado.empty();
            if (data.length < 1 || resp.success == false) {
                listado.append(
                    '<tr><td colspan="7">No se encontraron registros</td></tr>'
                );
            } else {

                for (var i = 0; i < data.length; i++) {

                    var item = data[i];

                    listado.append(
                        '<tr>' +
                        '<td>' + item['pac_identificacion'] + '</td>' +
                        '<td>' + item['pac_nombre_completo'] + '</td>' +
                        '<td>' + item['pac_telefono'] + '</td>' +
                        '<td>' +
                            '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_proceso" onclick="modal_proceso('+item['pac_id']+');">Proceso</button>'+
                            '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_perfil" onclick="modal_perfil('+item['pac_id']+');">Perfil</button>'+
                            '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editar_paciente" onclick="modal_editar('+item['pac_id']+');"><i class="fas fa-edit"></i></button>'+
                        '</td>' +
                        '</tr>'
                    );
                }
            }

        },
        error: function (jqXHR) {
            console.log('error!');
        }
    });

};



function modal_editar(pac_id) {
    let $select_departamento = document.getElementById('modal_dep_id')
    let $select_municipio = document.getElementById('modal_mun_id')

    sendDatos = {
        'pac_id': pac_id
    }

    $.ajax({
        url: '/pac/get',
        type: 'GET',
        dataType: 'json',
        data: sendDatos,
        /* beforeSend: function () {
            console.log('enviada');
        },
        complete: function () {
            console.log('completada');
        }, */
        success: function (response) {

            const departamentos = response.departamentos;
            document.getElementById('modal_pac_id').value = pac_id;

            document.getElementById('modal_pac_telefono').value = response.paciente.pac_telefono;

            document.getElementById('modal_tip_id').value = response.paciente.tip_alias;
            document.getElementById('modal_pac_identificacion').value = response.paciente.pac_identificacion;
            document.getElementById('modal_pac_nombre_completo').value = response.paciente.pac_nombre_completo;
            document.getElementById('modal_pac_fecha_nacimiento').value = response.paciente.pac_fecha_nacimiento;
            document.getElementById('modal_pac_sexo').value = response.paciente.pac_sexo;
            document.getElementById('modal_pac_regimen_afiliacion_SGSS').value = response.paciente.pac_regimen_afiliacion_SGSS;

            let template = '<option class="form-control" value="' + response.paciente.dep_id + '" selected>' + response.paciente.dep_nombre + '</option>';
            template += '<option class="form-control" disabled>-- Seleccione --</option>';

            departamentos.forEach(departamento => {
                template += `<option class="form-control" value="${departamento.dep_id}">${departamento.dep_nombre}</option>`;
            })

            $select_departamento.innerHTML = template;

            let template2 = '<option class="form-control" value="' + response.paciente.mun_id + '" selected>' + response.paciente.mun_nombre + '</option>';

            $select_municipio.innerHTML = template2;

            document.getElementById('modal_pac_direccion').value = response.paciente.pac_direccion;

        },
        error: function (jqXHR) {
            console.log('error!');
        }
    });

}

/* SELECT DINAMICO */

/* --variables para llamar a los select por el id */
let $select_departamento = document.getElementById('dep_id')
let $select_municipio = document.getElementById('mun_id')

let $select_departamento_modal = document.getElementById('modal_dep_id')
let $select_municipio_modal = document.getElementById('modal_mun_id')

function cargarMunicipios(sendDatos) {

    $.ajax({
        url: '/adm/combo/dep/mun',
        type: 'GET',
        dataType: 'json',
        data: sendDatos,
        success: function (response) {
            const respuestas = response.municipios;

            let template = '<option class="form-control" selected disabled>-- Seleccione --</option>'

            respuestas.forEach(respuesta => {
                template +=
                    `<option class="form-control" value="${respuesta.mun_id}">${respuesta.mun_nombre}</option>`;
            })

            $select_municipio.innerHTML = template;
        },
        error: function (jqXHR) {
            console.log('error!');
        }
    });

}

$select_departamento.addEventListener('change', () => {
    const dep_id = $select_departamento.value

    const sendDatos = {
        'dep_id': dep_id
    }

    cargarMunicipios(sendDatos)

})

/* SELECT DINAMICO2 */

function cargarMunicipios_modal(sendDatos) {

    $.ajax({
        url: '/adm/combo/dep/mun',
        type: 'GET',
        dataType: 'json',
        data: sendDatos,
        success: function (response) {
            const respuestas = response.municipios;

            let template2 = '<option class="form-control" selected disabled>-- Seleccione --</option>'

            respuestas.forEach(respuesta => {
                template2 += `<option class="form-control" value="${respuesta.mun_id}">${respuesta.mun_nombre}</option>`;
            })

            $select_municipio_modal.innerHTML = template2;
        },
        error: function (jqXHR) {
            console.log('error!');
        }
    });

}

$select_departamento_modal.addEventListener('change', () => {
    const dep_id = $select_departamento_modal.value

    const sendDatos = {
        'dep_id': dep_id
    }

    cargarMunicipios_modal(sendDatos)

})
