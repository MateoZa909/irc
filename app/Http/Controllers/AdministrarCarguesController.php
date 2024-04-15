<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;

use App\Exports\InasistidosExport;
use App\Exports\SeguimientoExport;
use App\Exports\RecordatorioExport;
use App\Exports\HospitalizadoExport;
use App\Exports\BrigadaExport;
use App\Exports\ReprogramacionExport;
use App\Exports\CaptacionExport;


class AdministrarCarguesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function descargar_acta($car_id){

        $sql = "SELECT `Acc_id`, `Acc_codigo`, `Acc_nombre`, `Acc_fecha_recepcion`, `Acc_leidos`, `Acc_duplicados`, `Acc_cargados`, `created_at`    
        FROM `actas_cargues`
        WHERE `car_id` = ".$car_id;

        $acta = DB::select($sql);

        $pdf = PDF::loadView('importar.pdf-correcto', compact('acta'));
        return $pdf->download($acta[0]->Acc_nombre.'_ACTA_CARGUE.pdf');
    }


    function get_cargue(request $request, $id){

        $tpp_id = $request->tpp_id;
        $file_name = $request->file_name;

        switch ($tpp_id) {
            case 1:
                /* INASISTIDOS */
                $data = $this->get_ina($tpp_id, $id);
                return Excel::download(new InasistidosExport($data), 'REPORTE-'.$file_name.'.xlsx');
                break;
            case 2:
                /* SEGUIMIENTOS */
                $data = $this->get_seg($tpp_id, $id);
                return Excel::download(new SeguimientoExport($data), 'REPORTE-'.$file_name.'.xlsx');
                break;
            case 3:
                /* RECORDATORIOS */
                $data = $this->get_rec($tpp_id, $id);
                return Excel::download(new RecordatorioExport($data), 'REPORTE-'.$file_name.'.xlsx');
                break;
            case 4:
                /* HOSPITALIZADOS */
                $data = $this->get_hos($tpp_id, $id);
                return Excel::download(new HospitalizadoExport($data), 'REPORTE-'.$file_name.'.xlsx');
                break;
            case 5:
                /* BRIGADA */
                $data = $this->get_bri($tpp_id, $id);
                return Excel::download(new BrigadaExport($data), 'REPORTE-'.$file_name.'.xlsx');
                break;
            case 6:
                /* REPROGRAMACION */
                $data = $this->get_rep($tpp_id, $id);
                return Excel::download(new ReprogramacionExport($data), 'REPORTE-'.$file_name.'.xlsx');
                break;
            case 7:
                /* CAPTACION */
                $data = $this->get_cap($tpp_id, $id);
                return Excel::download(new CaptacionExport($data), 'REPORTE-'.$file_name.'.xlsx');
                break;
            default:
                dd('error');
                break;
        }

    }

    function get_ina($tpp_id, $id){

        $sql_pacientes = "SELECT tin.tin_nombre,tin.tin_nombre, pro.*,
        ina.ina_medico_especialidad, ina.ina_fecha_cita, ina.ina_medico_nombre
        FROM cargues AS car
        INNER JOIN procesos AS pro ON pro.car_id = car.car_id
        INNER JOIN pacientes AS pac ON pac.pac_id = pro.pac_id
        INNER JOIN inasistidos AS ina ON ina.pro_id = pro.pro_id
        LEFT JOIN tipos_inasistencias AS tin ON tin.tin_id = ina.ina_motivo_inasistencia
        WHERE car.car_estado = 1
        AND car.car_id = ".$id."
        AND car.tpp_id = ".$tpp_id;

        $pacientes = DB::select($sql_pacientes);
        $data[] = array();
        $l2 = 0;
        for ($l=0; $l < count($pacientes); $l++) {

            $pac_id = $pacientes[$l2]->pac_id;

            $paciente = DB::select("SELECT tip.tip_alias, dep.dep_nombre, mun.mun_nombre, pac.*
            FROM pacientes AS pac
            INNER JOIN tipos_identificaciones AS tip ON pac.tip_id = tip.tip_id
            INNER JOIN departamentos AS dep ON dep.dep_id = pac.dep_id
            INNER JOIN municipios AS mun ON mun.mun_id = pac.mun_id
            WHERE pac_id = ".$pac_id);

            $registro = [
                "DPTO" => $paciente[0]->dep_nombre,
                "MUNICIPIO" => $paciente[0]->mun_nombre,
                "TIPO DE ID" => $paciente[0]->tip_alias,
                "NUMERO DE ID" => $paciente[0]->pac_identificacion,
                "REGIMEN" => $paciente[0]->pac_regimen_afiliacion_SGSS,
                "PRIMER NOMBRE" => $paciente[0]->pac_primer_nombre,
                "SEGUNDO NOMBRE" => $paciente[0]->pac_segundo_nombre,
                "PRIMER APELLIDO" => $paciente[0]->pac_primer_apellido,
                "SEGUNDO APELLIDO" => $paciente[0]->pac_segundo_apellido,
                "FECHA DE NACIMIENTO" => $paciente[0]->pac_fecha_nacimiento,
                "DIRECCION" => $paciente[0]->pac_direccion,
                "TELEFONICO" => $paciente[0]->pac_telefono,
                "FECHA CITA" => $pacientes[$l2]->ina_fecha_cita,
                "MEDICO" => $pacientes[$l2]->ina_medico_nombre,
                "ESPECIALIDAD" => $pacientes[$l2]->ina_medico_especialidad
            ];

            /* dd($list, $paciente[0], $registro); */

            $pro_id = $pacientes[$l2]->pro_id;

            $sql_gestiones = "SELECT tge.tge_nombre , ges.*
            FROM gestiones AS ges
            INNER JOIN procesos AS pro ON pro.pro_id = ges.pro_id
            INNER JOIN inasistidos AS ina ON ina.pro_id = pro.pro_id
            INNER JOIN tipos_gestiones AS tge ON tge.tge_id = ges.tge_id
            WHERE ges.ges_estado = 1
            AND ges.pro_id = ".$pro_id."
            ORDER BY ges.ges_fecha DESC
            LIMIT 3";

            $gestiones = DB::select($sql_gestiones);

            $v = count($gestiones)-1;

            $registro["SEGUIMIENTO 1"] = " ";
            $registro["FECHA DE SEGUIMIENTO 1"] = " ";
            $registro["SEGUIMIENTO 2"] = " ";
            $registro["FECHA DE SEGUIMIENTO 2"] = " ";
            $registro["SEGUIMIENTO 3"] = " ";
            $registro["FECHA DE SEGUIMIENTO 3"] = " ";

            if($v > -1){
                for ($i=1; $i < count($gestiones)+1; $i++) {

                    $registro["SEGUIMIENTO ".$i] = $gestiones[$v]->tge_nombre;
                    $registro["FECHA DE SEGUIMIENTO ".$i] = $gestiones[$v]->ges_fecha;
                    $v -= 1;
                }
            }

            $registro["MOTIVO DE INASISTENCIA"] = $pacientes[$l2]->tin_nombre;

            $ult_ges = $pacientes[$l2]->ges_id;

            $registro["ULTIMO COMENTARIO"] = " ";

            if($ult_ges != null){
                $fecha_cita = DB::select('SELECT `ges_comentario` FROM `gestiones` WHERE `ges_id` = '.$ult_ges);

                $registro["ULTIMO COMENTARIO"] = $fecha_cita[0]->ges_comentario;
            }



            $data[$l2] = $registro;

            /* dd($data, $registro, $pacientes, $sql_gestiones); */

            $l2 += 1;
        }

        /* dd('nashe get_ina', $data); */

        return $data;

    }

    function get_seg($tpp_id, $id){

        $sql_pacientes = "SELECT pro.*, seg.sdi_especialidad,
        seg.sdi_fecha_ultimo_control, seg.sdi_fecha_cita
        FROM cargues AS car
        INNER JOIN procesos AS pro ON pro.car_id = car.car_id
        INNER JOIN pacientes AS pac ON pac.pac_id = pro.pac_id
        INNER JOIN seguimientos_demandas_inducidas AS seg ON seg.pro_id = pro.pro_id
        WHERE car.car_estado = 1
        AND car.car_id = ".$id."
        AND car.tpp_id = ".$tpp_id;

        $pacientes = DB::select($sql_pacientes);
        $data[] = array();
        $l2 = 0;
        for ($l=0; $l < count($pacientes); $l++) {

            $pac_id = $pacientes[$l2]->pac_id;

            $paciente = DB::select("SELECT tip.tip_alias, dep.dep_nombre, mun.mun_nombre, pac.*
            FROM pacientes AS pac
            INNER JOIN tipos_identificaciones AS tip ON pac.tip_id = tip.tip_id
            INNER JOIN departamentos AS dep ON dep.dep_id = pac.dep_id
            INNER JOIN municipios AS mun ON mun.mun_id = pac.mun_id
            WHERE pac_id = ".$pac_id);

            $registro = [
                "DPTO" => $paciente[0]->dep_nombre,
                "MUNICIPIO" => $paciente[0]->mun_nombre,
                "TIPO DE ID" => $paciente[0]->tip_alias,
                "NUMERO DE ID" => $paciente[0]->pac_identificacion,
                "REGIMEN" => $paciente[0]->pac_regimen_afiliacion_SGSS,
                "PRIMER NOMBRE" => $paciente[0]->pac_primer_nombre,
                "SEGUNDO NOMBRE" => $paciente[0]->pac_segundo_nombre,
                "PRIMER APELLIDO" => $paciente[0]->pac_primer_apellido,
                "SEGUNDO APELLIDO" => $paciente[0]->pac_segundo_apellido,
                "FECHA DE NACIMIENTO" => $paciente[0]->pac_fecha_nacimiento,
                "DIRECCION" => $paciente[0]->pac_direccion,
                "TELEFONICO" => $paciente[0]->pac_telefono,
                "ESPECIALIDAD" => $pacientes[$l2]->sdi_especialidad,
                "FECHA ULTIMO CONTROL" => $pacientes[$l2]->sdi_fecha_ultimo_control,
            ];

            /* dd($list, $paciente[0], $registro); */

            $pro_id = $pacientes[$l2]->pro_id;

            $sql_gestiones = "SELECT tge.tge_nombre , ges.*
            FROM gestiones AS ges
            INNER JOIN procesos AS pro ON pro.pro_id = ges.pro_id
            INNER JOIN tipos_gestiones AS tge ON tge.tge_id = ges.tge_id
            WHERE ges.ges_estado = 1
            AND ges.pro_id = ".$pro_id."
            ORDER BY ges.ges_fecha DESC
            LIMIT 3";

            $gestiones = DB::select($sql_gestiones);

            $v = count($gestiones)-1;

            $registro["SEGUIMIENTO 1"] = " ";
            $registro["FECHA DE SEGUIMIENTO 1"] = " ";
            $registro["SEGUIMIENTO 2"] = " ";
            $registro["FECHA DE SEGUIMIENTO 2"] = " ";
            $registro["SEGUIMIENTO 3"] = " ";
            $registro["FECHA DE SEGUIMIENTO 3"] = " ";

            if($v > -1){
                for ($i=1; $i < count($gestiones)+1; $i++) {
                    $registro["SEGUIMIENTO ".$i] = $gestiones[$v]->tge_nombre;
                    $registro["FECHA DE SEGUIMIENTO ".$i] = $gestiones[$v]->ges_fecha;
                    $v -= 1;
                }
            }

            $ult_ges = $pacientes[$l2]->ges_id;

            $registro["FECHA NUEVA CITA"] = " ";

            if($ult_ges != null){
                $fecha_cita = DB::select('SELECT `ges_fecha_nueva_cita` FROM `gestiones` WHERE `ges_id` = '.$ult_ges);

                $registro["FECHA NUEVA CITA"] = $fecha_cita[0]->ges_fecha_nueva_cita;
            }

            $ult_ges = $pacientes[$l2]->ges_id;

            $registro["ULTIMO COMENTARIO"] = " ";

            if($ult_ges != null){
                $fecha_cita = DB::select('SELECT `ges_comentario` FROM `gestiones` WHERE `ges_id` = '.$ult_ges);

                $registro["ULTIMO COMENTARIO"] = $fecha_cita[0]->ges_comentario;
            }



            $data[$l2] = $registro;

            /* dd($data, $registro, $pacientes, $sql_gestiones); */

            $l2 += 1;
        }

        /* dd('nashe get_ina', $data); */

        return $data;

    }

    function get_rec($tpp_id, $id){
        $sql_pacientes = "SELECT rec.*, pro.pac_id, pro.pro_id, pro.ges_id
        FROM cargues AS car
        INNER JOIN procesos AS pro ON pro.car_id = car.car_id
        INNER JOIN pacientes AS pac ON pac.pac_id = pro.pac_id
        INNER JOIN recordatorios AS rec ON rec.pro_id = pro.pro_id
        WHERE car.car_estado = 1
        AND car.car_id = ".$id."
        AND car.tpp_id = ".$tpp_id;

        $pacientes = DB::select($sql_pacientes);
        $data[] = array();
        $l2 = 0;
        for ($l=0; $l < count($pacientes); $l++) {

            $pac_id = $pacientes[$l2]->pac_id;

            $paciente = DB::select("SELECT tip.tip_alias, dep.dep_nombre, mun.mun_nombre, pac.*
            FROM pacientes AS pac
            INNER JOIN tipos_identificaciones AS tip ON pac.tip_id = tip.tip_id
            INNER JOIN departamentos AS dep ON dep.dep_id = pac.dep_id
            INNER JOIN municipios AS mun ON mun.mun_id = pac.mun_id
            WHERE pac_id = ".$pac_id);

            $registro = [
                "DPTO" => $paciente[0]->dep_nombre,
                "MUNICIPIO" => $paciente[0]->mun_nombre,
                "TIPO DE ID" => $paciente[0]->tip_alias,
                "NUMERO DE ID" => $paciente[0]->pac_identificacion,
                "REGIMEN" => $paciente[0]->pac_regimen_afiliacion_SGSS,
                "PRIMER NOMBRE" => $paciente[0]->pac_primer_nombre,
                "SEGUNDO NOMBRE" => $paciente[0]->pac_segundo_nombre,
                "PRIMER APELLIDO" => $paciente[0]->pac_primer_apellido,
                "SEGUNDO APELLIDO" => $paciente[0]->pac_segundo_apellido,
                "FECHA DE NACIMIENTO" => $paciente[0]->pac_fecha_nacimiento,
                "DIRECCION" => $paciente[0]->pac_direccion,
                "TELEFONICO" => $paciente[0]->pac_telefono,
                "FECHA CITA" => $pacientes[$l2]->rec_fecha_cita,
                "CONVENIO" => $pacientes[$l2]->rec_convenio,
                "ESPECIALIDAD" => $pacientes[$l2]->rec_especialidad,
                "PROFESIONAL" => $pacientes[$l2]->rec_profesional,
                "MODALIDAD" => $pacientes[$l2]->rec_modalidad,
                "PYM" => $pacientes[$l2]->rec_pym
            ];

            /* dd($list, $paciente[0], $registro); */

            $pro_id = $pacientes[$l2]->pro_id;

            $sql_gestiones = "SELECT tge.tge_nombre , ges.*
            FROM gestiones AS ges
            INNER JOIN procesos AS pro ON pro.pro_id = ges.pro_id
            INNER JOIN tipos_gestiones AS tge ON tge.tge_id = ges.tge_id
            WHERE ges.ges_estado = 1
            AND ges.pro_id = ".$pro_id."
            ORDER BY ges.ges_fecha DESC
            LIMIT 3";

            $gestiones = DB::select($sql_gestiones);

            $v = count($gestiones)-1;

            $registro["SEGUIMIENTO 1"] = " ";
            $registro["FECHA DE SEGUIMIENTO 1"] = " ";
            $registro["SEGUIMIENTO 2"] = " ";
            $registro["FECHA DE SEGUIMIENTO 2"] = " ";
            $registro["SEGUIMIENTO 3"] = " ";
            $registro["FECHA DE SEGUIMIENTO 3"] = " ";

            if($v > -1){
                for ($i=1; $i < count($gestiones)+1; $i++) {
                    $registro["SEGUIMIENTO ".$i] = $gestiones[$v]->tge_nombre;
                    $registro["FECHA DE SEGUIMIENTO ".$i] = $gestiones[$v]->ges_fecha;
                    $v -= 1;
                }
            }

            $ult_ges = $pacientes[$l2]->ges_id;

            $registro["ULTIMO COMENTARIO"] = " ";

            if($ult_ges != null){
                $fecha_cita = DB::select('SELECT `ges_comentario` FROM `gestiones` WHERE `ges_id` = '.$ult_ges);

                $registro["ULTIMO COMENTARIO"] = $fecha_cita[0]->ges_comentario;
            }



            $data[$l2] = $registro;

            /* dd($data, $registro, $pacientes, $sql_gestiones); */

            $l2 += 1;
        }

        return $data;
    }

    function get_hos($tpp_id, $id){
        $sql_pacientes = "SELECT hos.*, pro.pac_id, pro.pro_id, pro.ges_id
        FROM cargues AS car
        INNER JOIN procesos AS pro ON pro.car_id = car.car_id
        INNER JOIN pacientes AS pac ON pac.pac_id = pro.pac_id
        INNER JOIN hospitalizados AS hos ON hos.pro_id = pro.pro_id
        WHERE car.car_estado = 1
        AND car.car_id = ".$id."
        AND car.tpp_id = ".$tpp_id;

        $pacientes = DB::select($sql_pacientes);
        $data[] = array();
        $l2 = 0;
        for ($l=0; $l < count($pacientes); $l++) {

            $pac_id = $pacientes[$l2]->pac_id;

            $paciente = DB::select("SELECT tip.tip_alias, dep.dep_nombre, mun.mun_nombre, pac.*
            FROM pacientes AS pac
            INNER JOIN tipos_identificaciones AS tip ON pac.tip_id = tip.tip_id
            INNER JOIN departamentos AS dep ON dep.dep_id = pac.dep_id
            INNER JOIN municipios AS mun ON mun.mun_id = pac.mun_id
            WHERE pac_id = ".$pac_id);

            $registro = [
                "DPTO" => $paciente[0]->dep_nombre,
                "MUNICIPIO" => $paciente[0]->mun_nombre,
                "TIPO DE ID" => $paciente[0]->tip_alias,
                "NUMERO DE ID" => $paciente[0]->pac_identificacion,
                "REGIMEN" => $paciente[0]->pac_regimen_afiliacion_SGSS,
                "PRIMER NOMBRE" => $paciente[0]->pac_primer_nombre,
                "SEGUNDO NOMBRE" => $paciente[0]->pac_segundo_nombre,
                "PRIMER APELLIDO" => $paciente[0]->pac_primer_apellido,
                "SEGUNDO APELLIDO" => $paciente[0]->pac_segundo_apellido,
                "FECHA DE NACIMIENTO" => $paciente[0]->pac_fecha_nacimiento,
                "DIRECCION" => $paciente[0]->pac_direccion,
                "TELEFONICO" => $paciente[0]->pac_telefono,
                "DIAGNOSTICO" => $pacientes[$l2]->hos_diagnostico,
                "FECHA INGRESO" => $pacientes[$l2]->hos_fecha_ingreso,
                "FECHA EGRESO" => $pacientes[$l2]->hos_fecha_egreso,
                "PROGRAMA" => $pacientes[$l2]->hos_programa,
                "PERTENECE A IRC" => $pacientes[$l2]->hos_pertenece_irc
            ];

            /* dd($list, $paciente[0], $registro); */

            $pro_id = $pacientes[$l2]->pro_id;

            $sql_gestiones = "SELECT tge.tge_nombre , ges.*
            FROM gestiones AS ges
            INNER JOIN procesos AS pro ON pro.pro_id = ges.pro_id
            INNER JOIN tipos_gestiones AS tge ON tge.tge_id = ges.tge_id
            WHERE ges.ges_estado = 1
            AND ges.pro_id = ".$pro_id."
            ORDER BY ges.ges_fecha DESC
            LIMIT 3";

            $gestiones = DB::select($sql_gestiones);

            $v = count($gestiones)-1;

            $registro["SEGUIMIENTO 1"] = " ";
            $registro["FECHA DE SEGUIMIENTO 1"] = " ";
            $registro["SEGUIMIENTO 2"] = " ";
            $registro["FECHA DE SEGUIMIENTO 2"] = " ";
            $registro["SEGUIMIENTO 3"] = " ";
            $registro["FECHA DE SEGUIMIENTO 3"] = " ";

            if($v > -1){
                for ($i=1; $i < count($gestiones)+1; $i++) {
                    $registro["SEGUIMIENTO ".$i] = $gestiones[$v]->tge_nombre;
                    $registro["FECHA DE SEGUIMIENTO ".$i] = $gestiones[$v]->ges_fecha;
                    $v -= 1;
                }
            }

            $ult_ges = $pacientes[$l2]->ges_id;

            $registro["ULTIMO COMENTARIO"] = " ";

            if($ult_ges != null){
                $fecha_cita = DB::select('SELECT `ges_comentario` FROM `gestiones` WHERE `ges_id` = '.$ult_ges);

                $registro["ULTIMO COMENTARIO"] = $fecha_cita[0]->ges_comentario;
            }


            $data[$l2] = $registro;

            /* dd($data, $registro, $pacientes, $sql_gestiones); */

            $l2 += 1;
        }

        return $data;
    }

    function get_bri($tpp_id, $id){
        $sql_pacientes = "SELECT bri.*, pro.pac_id, pro.pro_id, pro.ges_id
        FROM cargues AS car
        INNER JOIN procesos AS pro ON pro.car_id = car.car_id
        INNER JOIN pacientes AS pac ON pac.pac_id = pro.pac_id
        INNER JOIN brigadas AS bri ON bri.pro_id = pro.pro_id
        WHERE car.car_estado = 1
        AND car.car_id = ".$id."
        AND car.tpp_id = ".$tpp_id;

        $pacientes = DB::select($sql_pacientes);
        $data[] = array();
        $l2 = 0;
        for ($l=0; $l < count($pacientes); $l++) {

            $pac_id = $pacientes[$l2]->pac_id;

            $paciente = DB::select("SELECT tip.tip_alias, dep.dep_nombre, mun.mun_nombre, pac.*
            FROM pacientes AS pac
            INNER JOIN tipos_identificaciones AS tip ON pac.tip_id = tip.tip_id
            INNER JOIN departamentos AS dep ON dep.dep_id = pac.dep_id
            INNER JOIN municipios AS mun ON mun.mun_id = pac.mun_id
            WHERE pac_id = ".$pac_id);

            $registro = [
                "DPTO" => $paciente[0]->dep_nombre,
                "MUNICIPIO" => $paciente[0]->mun_nombre,
                "TIPO DE ID" => $paciente[0]->tip_alias,
                "NUMERO DE ID" => $paciente[0]->pac_identificacion,
                "REGIMEN" => $paciente[0]->pac_regimen_afiliacion_SGSS,
                "PRIMER NOMBRE" => $paciente[0]->pac_primer_nombre,
                "SEGUNDO NOMBRE" => $paciente[0]->pac_segundo_nombre,
                "PRIMER APELLIDO" => $paciente[0]->pac_primer_apellido,
                "SEGUNDO APELLIDO" => $paciente[0]->pac_segundo_apellido,
                "FECHA DE NACIMIENTO" => $paciente[0]->pac_fecha_nacimiento,
                "DIRECCION" => $paciente[0]->pac_direccion,
                "TELEFONICO" => $paciente[0]->pac_telefono,
                "FECHA BRIGADA" => $pacientes[$l2]->bri_fecha,
                "CONVENIO" => $pacientes[$l2]->bri_convenio,
                "PUNTO ACOPIO" => $pacientes[$l2]->bri_punto_acopio,
                "ESPECIALIDAD" => $pacientes[$l2]->bri_especialidad,
                "FECHA ULTIMO CONTROL" => $pacientes[$l2]->bri_fecha_ultimo_control,
                "DIAS TRANSCURRIDOS" => $pacientes[$l2]->bri_dias_transcurrido,
                "FECHA CITA" => $pacientes[$l2]->bri_fecha_cita
            ];

            /* dd($list, $paciente[0], $registro); */

            $pro_id = $pacientes[$l2]->pro_id;

            $sql_gestiones = "SELECT tge.tge_nombre , ges.*
            FROM gestiones AS ges
            INNER JOIN procesos AS pro ON pro.pro_id = ges.pro_id
            INNER JOIN tipos_gestiones AS tge ON tge.tge_id = ges.tge_id
            WHERE ges.ges_estado = 1
            AND ges.pro_id = ".$pro_id."
            ORDER BY ges.ges_fecha DESC
            LIMIT 3";

            $gestiones = DB::select($sql_gestiones);

            $v = count($gestiones)-1;

            $registro["SEGUIMIENTO 1"] = " ";
            $registro["FECHA DE SEGUIMIENTO 1"] = " ";
            $registro["SEGUIMIENTO 2"] = " ";
            $registro["FECHA DE SEGUIMIENTO 2"] = " ";
            $registro["SEGUIMIENTO 3"] = " ";
            $registro["FECHA DE SEGUIMIENTO 3"] = " ";

            if($v > -1){
                for ($i=1; $i < count($gestiones)+1; $i++) {
                    $registro["SEGUIMIENTO ".$i] = $gestiones[$v]->tge_nombre;
                    $registro["FECHA DE SEGUIMIENTO ".$i] = $gestiones[$v]->ges_fecha;
                    $v -= 1;
                }
            }

            $ult_ges = $pacientes[$l2]->ges_id;

            $registro["ULTIMO COMENTARIO"] = " ";

            if($ult_ges != null){
                $fecha_cita = DB::select('SELECT `ges_comentario` FROM `gestiones` WHERE `ges_id` = '.$ult_ges);

                $registro["ULTIMO COMENTARIO"] = $fecha_cita[0]->ges_comentario;
            }



            $data[$l2] = $registro;

            /* dd($data, $registro, $pacientes, $sql_gestiones); */

            $l2 += 1;
        }

        return $data;
    }

    function get_rep($tpp_id, $id){
        $sql_pacientes = "SELECT rep.*, pro.pac_id, pro.pro_id, pro.ges_id
        FROM cargues AS car
        INNER JOIN procesos AS pro ON pro.car_id = car.car_id
        INNER JOIN pacientes AS pac ON pac.pac_id = pro.pac_id
        INNER JOIN reprogramaciones AS rep ON rep.pro_id = pro.pro_id
        WHERE car.car_estado = 1
        AND car.car_id = ".$id."
        AND car.tpp_id = ".$tpp_id;

        $pacientes = DB::select($sql_pacientes);
        $data[] = array();
        $l2 = 0;
        for ($l=0; $l < count($pacientes); $l++) {

            $pac_id = $pacientes[$l2]->pac_id;

            $paciente = DB::select("SELECT tip.tip_alias, dep.dep_nombre, mun.mun_nombre, pac.*
            FROM pacientes AS pac
            INNER JOIN tipos_identificaciones AS tip ON pac.tip_id = tip.tip_id
            INNER JOIN departamentos AS dep ON dep.dep_id = pac.dep_id
            INNER JOIN municipios AS mun ON mun.mun_id = pac.mun_id
            WHERE pac_id = ".$pac_id);

            $registro = [
                "DPTO" => $paciente[0]->dep_nombre,
                "MUNICIPIO" => $paciente[0]->mun_nombre,
                "TIPO DE ID" => $paciente[0]->tip_alias,
                "NUMERO DE ID" => $paciente[0]->pac_identificacion,
                "REGIMEN" => $paciente[0]->pac_regimen_afiliacion_SGSS,
                "PRIMER NOMBRE" => $paciente[0]->pac_primer_nombre,
                "SEGUNDO NOMBRE" => $paciente[0]->pac_segundo_nombre,
                "PRIMER APELLIDO" => $paciente[0]->pac_primer_apellido,
                "SEGUNDO APELLIDO" => $paciente[0]->pac_segundo_apellido,
                "FECHA DE NACIMIENTO" => $paciente[0]->pac_fecha_nacimiento,
                "DIRECCION" => $paciente[0]->pac_direccion,
                "TELEFONICO" => $paciente[0]->pac_telefono,
                "CONVENIO" => $pacientes[$l2]->rep_convenio,
                "FECHA CITA" => $pacientes[$l2]->rep_fecha_cita,
                "ESPECIALIDAD" => $pacientes[$l2]->rep_especialidad,
                "NUEVA CITA" => $pacientes[$l2]->rep_nueva_cita,
                "PROFESIONAL" => $pacientes[$l2]->rep_profesional
            ];

            /* dd($list, $paciente[0], $registro); */

            $pro_id = $pacientes[$l2]->pro_id;

            $sql_gestiones = "SELECT tge.tge_nombre , ges.*
            FROM gestiones AS ges
            INNER JOIN procesos AS pro ON pro.pro_id = ges.pro_id
            INNER JOIN tipos_gestiones AS tge ON tge.tge_id = ges.tge_id
            WHERE ges.ges_estado = 1
            AND ges.pro_id = ".$pro_id."
            ORDER BY ges.ges_fecha DESC
            LIMIT 3";

            $gestiones = DB::select($sql_gestiones);

            $v = count($gestiones)-1;

            $registro["SEGUIMIENTO 1"] = " ";
            $registro["FECHA DE SEGUIMIENTO 1"] = " ";
            $registro["SEGUIMIENTO 2"] = " ";
            $registro["FECHA DE SEGUIMIENTO 2"] = " ";
            $registro["SEGUIMIENTO 3"] = " ";
            $registro["FECHA DE SEGUIMIENTO 3"] = " ";

            if($v > -1){
                for ($i=1; $i < count($gestiones)+1; $i++) {
                    $registro["SEGUIMIENTO ".$i] = $gestiones[$v]->tge_nombre;
                    $registro["FECHA DE SEGUIMIENTO ".$i] = $gestiones[$v]->ges_fecha;
                    $v -= 1;
                }
            }

            $ult_ges = $pacientes[$l2]->ges_id;

            $registro["ULTIMO COMENTARIO"] = " ";

            if($ult_ges != null){
                $fecha_cita = DB::select('SELECT `ges_comentario` FROM `gestiones` WHERE `ges_id` = '.$ult_ges);

                $registro["ULTIMO COMENTARIO"] = $fecha_cita[0]->ges_comentario;
            }



            $data[$l2] = $registro;

            /* dd($data, $registro, $pacientes, $sql_gestiones); */

            $l2 += 1;
        }

        return $data;
    }

    function get_cap($tpp_id, $id){
        $sql_pacientes = "SELECT pro.*
        FROM cargues AS car
        INNER JOIN procesos AS pro ON pro.car_id = car.car_id
        INNER JOIN pacientes AS pac ON pac.pac_id = pro.pac_id
        WHERE car.car_estado = 1
        AND car.car_id = ".$id."
        AND car.tpp_id = ".$tpp_id;

        $pacientes = DB::select($sql_pacientes);
        $data[] = array();
        $l2 = 0;

        for ($l=0; $l < count($pacientes); $l++) {

            $pac_id = $pacientes[$l2]->pac_id;

            $paciente = DB::select("SELECT tip.tip_alias, dep.dep_nombre, mun.mun_nombre, pac.*
            FROM pacientes AS pac
            INNER JOIN tipos_identificaciones AS tip ON pac.tip_id = tip.tip_id
            INNER JOIN departamentos AS dep ON dep.dep_id = pac.dep_id
            INNER JOIN municipios AS mun ON mun.mun_id = pac.mun_id
            WHERE pac_id = ".$pac_id);

            $registro = [
                "DPTO" => $paciente[0]->dep_nombre,
                "MUNICIPIO" => $paciente[0]->mun_nombre,
                "TIPO DE ID" => $paciente[0]->tip_alias,
                "NUMERO DE ID" => $paciente[0]->pac_identificacion,
                "REGIMEN" => $paciente[0]->pac_regimen_afiliacion_SGSS,
                "PRIMER NOMBRE" => $paciente[0]->pac_primer_nombre,
                "SEGUNDO NOMBRE" => $paciente[0]->pac_segundo_nombre,
                "PRIMER APELLIDO" => $paciente[0]->pac_primer_apellido,
                "SEGUNDO APELLIDO" => $paciente[0]->pac_segundo_apellido,
                "FECHA DE NACIMIENTO" => $paciente[0]->pac_fecha_nacimiento,
                "DIRECCION" => $paciente[0]->pac_direccion,
                "TELEFONICO" => $paciente[0]->pac_telefono
            ];

            /* dd($list, $paciente[0], $registro); */

            $pro_id = $pacientes[$l2]->pro_id;

            $sql_gestiones = "SELECT tge.tge_nombre , ges.*
            FROM gestiones AS ges
            INNER JOIN procesos AS pro ON pro.pro_id = ges.pro_id
            INNER JOIN tipos_gestiones AS tge ON tge.tge_id = ges.tge_id
            WHERE ges.ges_estado = 1
            AND ges.pro_id = ".$pro_id."
            ORDER BY ges.ges_fecha DESC
            LIMIT 3";

            $gestiones = DB::select($sql_gestiones);

            $v = count($gestiones)-1;

            $registro["SEGUIMIENTO 1"] = " ";
            $registro["FECHA DE SEGUIMIENTO 1"] = " ";
            $registro["SEGUIMIENTO 2"] = " ";
            $registro["FECHA DE SEGUIMIENTO 2"] = " ";
            $registro["SEGUIMIENTO 3"] = " ";
            $registro["FECHA DE SEGUIMIENTO 3"] = " ";

            if($v > -1){
                for ($i=1; $i < count($gestiones)+1; $i++) {
                    $registro["SEGUIMIENTO ".$i] = $gestiones[$v]->tge_nombre;
                    $registro["FECHA DE SEGUIMIENTO ".$i] = $gestiones[$v]->ges_fecha;
                    $v -= 1;
                }
            }

            $ult_ges = $pacientes[$l2]->ges_id;

            $registro["ULTIMO COMENTARIO"] = " ";

            if($ult_ges != null){
                $fecha_cita = DB::select('SELECT `ges_comentario` FROM `gestiones` WHERE `ges_id` = '.$ult_ges);

                $registro["ULTIMO COMENTARIO"] = $fecha_cita[0]->ges_comentario;
            }


            $data[$l2] = $registro;

            /* dd($data, $registro, $pacientes, $sql_gestiones); */

            $l2 += 1;
        }

        return $data;
    }

}
