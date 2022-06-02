<?php

namespace App\Http\Controllers\ControlPresupuestal;

use Illuminate\Http\Request;
use App\Http\Controllers\ControlPresupuestal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CentrosTrabajoController extends Controller
{
    //
    public function solicitado(Request $request){
        $numerovista = 95;
        $permisos = $request->session()->get('permisos');
        $CCPA = DB::table('Partidas')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('CCPA', 'ASC')
        ->get();
        $Descrip = DB::table('Partidas')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('Descripción', 'ASC')
        ->get();
        if (in_array($numerovista, $permisos)) {
            return view('pages.ControlPresupuestal.Centros-de-trabajo.solicitado', compact('CCPA', 'Descrip'));
        }else{
          return view('pages.contenido-restringido');
        }
    }

    public function buscarSolicitado(Request $request){
        $Año = session()->get('anioPeriodo');
        $consulta = DB::select("SELECT pm.*, P1.Descrip AS D1, P2.Descrip AS D2, P3.Descrip AS D3
            FROM PresupMaestro pm 
            LEFT OUTER JOIN PTOCAP AS P1 ON P1.Clave = pm.CAPITULO
            LEFT OUTER JOIN PTOCONC AS P2 ON P2.Clave = pm.CONCEPTO 
            LEFT OUTER JOIN PresupProg AS P3 ON P3.Prog = pm.PROG AND P3.SubProg = pm.SUBPROG
            WHERE pm.UBPP = ".$request->UBPP."AND pm.CCPA =".$request->CCPA."AND P1.Año = ".$Año." AND P2.Año =".$Año."AND pm.Año =".$Año);
        return json_encode($consulta);
    }

    public function guardarSolicitado(Request $request){
        $nueva = DB::table('PresupMaestro')
        ->where('UBPP', $request->UBPP)
        ->where('CCPA', $request->CCPA)
        ->where('Año', session()->get('anioPeriodo'))
        ->update(['PA1' => $request->PA1,
                 'PA2' => $request->PA2,
                 'PA3' => $request->PA3,
                 'PA4' => $request->PA4,
                 'PA5' => $request->PA5,
                 'PA6' => $request->PA6,
                 'PA7' => $request->PA7,
                 'PA8' => $request->PA8,
                 'PA9' => $request->PA9,
                 'PA10' => $request->PA10,
                 'PA11' => $request->PA11,
                 'PA12' => $request->PA12]);
        return $nueva;
    }

    public function ejercidos(Request $request){
        $numerovista = 99;
        $UBPP = DB::table('PresupUBPP')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('UBPP', 'ASC')
        ->get();
        $DescripUBPP = DB::table('PresupUBPP')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('Descrip', 'ASC')
        ->get();
        $CCPA = DB::table('Partidas')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('CCPA', 'ASC')
        ->get();
        $DescripCCPA = DB::table('Partidas')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('Descripción', 'ASC')
        ->get();
        $permisos = $request->session()->get('permisos');
        if (in_array($numerovista, $permisos)) {
            return view('pages.ControlPresupuestal.Centros-de-trabajo.ejercidos', compact('UBPP', 'DescripUBPP', 'CCPA', 'DescripCCPA'));
        }else{
          return view('pages.contenido-restringido');
        }
    }

    public function buscaFolioEjercidos(Request $request){
        ini_set('max_execution_time', 300);
        $anioPeriodo    = $request->session()->get('anioPeriodo');
        $respuestaData  = '';
        $tabla          = '';
        $fechaFolio     = '';
        $editar = '';
        $solvenciaTabla = 0;
        $solvencia      = 0;
        $NvaSolvencia   = 0;
        $tipo           = 0;
        $LBlnAct        = false;
        if( empty( $request->folio ) || $request->folio < 0 ){
            $respuestaData = ['error' => 'Por favor ingrese un folio valido'];
            return $respuestaData;
        }else{
            // consultamos la vista recibida
            switch ($request->vista) {
                case 'ejercidos':
                    $tipo = 1;
                    break;
                case 'compromisos':
                    $tipo = 2;
                    break;
            }
            $respuestaFolio = DB::table('PresupMov')
                                ->join('Partidas', function ($join) {//buscar por que hace join con partidas -03/02/22
                                    $join->on('PresupMov.CCPA', '=', 'Partidas.CCPA');
                                    $join->on('PresupMov.Año', '=', 'Partidas.Año');
                                })
                                ->select('*')
                                    ->where('PresupMov.FOLIO', $request->folio )
                                    ->where('PresupMov.AÑO', $anioPeriodo )
                                    ->where('PresupMov.TIPO', $tipo )
                                    ->orderBy('PresupMov.Cons', 'asc')
            ->get();
            if( !$respuestaFolio->count() ){
                $respuestaData = ['error' => 'No se Encontro el Folio: '.$request->folio];
            }else{
                foreach ($respuestaFolio as $key){
                    $fechaFolio = $key->FECHA;
                    switch ($request->vista) {    
                        case 'ejercidos':
                            if( $key->ESTADO == "A" || $key->ESTADO == "E" ){
                                $LBlnAct = true;
                            }
                            break;
                    }
                    // obtenemos la solvencia
                    $solvencia = $this->validaImp( $key->UBPP, $key->CCPA,  $key->MES, $anioPeriodo );
                    $solvenciaTabla = $this->validaImp( $key->UBPP, $key->CCPA,  $key->MES, $anioPeriodo );
                    if( $LBlnAct ){
                        $NvaSolvencia = $solvencia;
                        $solvencia >= 0 ? $NvaSolvencia = '<span class="text-primary">'.$NvaSolvencia.'<span>' : $NvaSolvencia = '<span class="text-danger">'.$NvaSolvencia.'<span>';
                    }else{
                        // obtenemos nueva solvencia
                        if( $key->FOL_COM != 0 ){
                            if( $key->MES != $key->MES_COM ){
                                if( $key->IMPORTE <= $key->Imp_Com ){
                                    $solvenciaTabla = $solvencia + $key->IMPORTE;
                                    $NvaSolvencia = $solvencia;
                                }else{
                                    $solvenciaTabla = $solvencia + $key->Imp_Com;
                                    $NvaSolvencia = $solvencia + $key->Imp_Com - $key->IMPORTE;
                                }
                            }else{
                                $NvaSolvencia = $solvencia - $key->IMPORTE;
                            }
                        }else{
                            $NvaSolvencia = $solvencia - $key->IMPORTE;
                        }
                        $solvencia - $key->IMPORTE >= 0 ? $NvaSolvencia = '<span class="text-primary">'.$NvaSolvencia.'<span>' : $NvaSolvencia = '<span class="text-danger">'.$NvaSolvencia.'<span>';
                    }
                    // si la vista es Compromisos
                    if( $request->vista == 'compromisos' ){
                        if( $key->ESTADO == "A" || $key->ESTADO == "C" || $key->ESTADO == "E" ){
                            $ConsultaPa = DB::table('PresupMaestro')
                            ->select(DB::raw('PA'.$key->MES.' as pa '))
                            ->where('año', $anioPeriodo)
                            ->where('UBPP', $key->UBPP)
                            ->where('CCPA', $key->CCPA)
                            ->get();
                            $NvaSolvencia = $solvencia;
                            $solvenciaTabla = $ConsultaPa[0]->pa;
                        }
                    }
                    // construimos nuestra tabla
                    if($key->ESTADO == "A" || $key->ESTADO == "E"){
                    $tabla = $tabla."<tr>
                                        <td></td>
                                        <td>".$key->UBPP."</td>
                                        <td>".$key->CCPA."</td>
                                        <td>".$key->Descripción."</td>
                                        <td>".sprintf('%.2f', $solvenciaTabla)."</td>
                                        <td>".sprintf('%.2f',$key->IMPORTE)."</td>
                                        <td>".$NvaSolvencia."</td>
                                        <td>".$key->CONS."</td>
                                        <td>".$key->MES."</td>
                                        <td>".$key->Concepto."</td>
                                        <td>".$key->Observaciones."</td>
                                        <td>".$key->Requisicion."</td>
                                        <td>".$key->FOL_COM."</td>
                                        <td>".$key->CONS_COM."</td>
                                        <td>".$key->MES_COM."</td>
                                        <td>".sprintf('%.2f',$key->Imp_Com)."</td>
                                    </tr>";
                        $editar = false;
                    }else{
                        $editar = true;
                        $tabla = $tabla."<tr>
                                            <td><button type='submit' id='editar' class='btn btn-light-primary font-weight-bold'>Editar</button></td>
                                            <td>".$key->UBPP."</td>
                                            <td>".$key->CCPA."</td>
                                            <td>".$key->Descripción."</td>
                                            <td>".sprintf('%.2f', $solvenciaTabla)."</td>
                                            <td>".sprintf('%.2f',$key->IMPORTE)."</td>
                                            <td>".$NvaSolvencia."</td>
                                            <td>".$key->CONS."</td>
                                            <td>".$key->MES."</td>
                                            <td>".$key->Concepto."</td>
                                            <td>".$key->Observaciones."</td>
                                            <td>".$key->Requisicion."</td>
                                            <td>".$key->FOL_COM."</td>
                                            <td>".$key->CONS_COM."</td>
                                            <td>".$key->MES_COM."</td>
                                            <td>".sprintf('%.2f',$key->Imp_Com)."</td>
                                        </tr>";
                    }
                }
            }
            $respuestaData = ['error' => '','tabla' =>  $tabla, 'fecha' => $fechaFolio, 'editar' => $editar];
            return $respuestaData;
        }
    }

    public function agregarTablaEjercidos( Request $request ){
        $anioPeriodo        = $request->session()->get('anioPeriodo');
        $tabla              ='';
        $nuevaSolvencia     = 0;
        $selectPresup       = '';
        $selectPresupRaw    = '';
        // validamos que los datos recibidos no esten vacios
        if(empty($request->ubpp) || empty($request->ccpa) || empty($request->mes) || empty($request->importe) || empty($request->observaciones)){
            $respuestaData = ['error' => 'No puede enviar campos vacios'];
            return $respuestaData;
        }
        // verificamos que el UBPP y CCPA recibidos sea validos
        $ubpp = $request->ubpp;
        $ccpa = explode("-", $request->ccpa);
        $denominacion = $request->desc;
        $ccpa = $ccpa[0];
        $patron = "/[0-9]+/";
        preg_match($patron, $ubpp, $ubpp);
        $ubpp =  $ubpp[0];
        $respuestaUbpp = DB::table('PresupMaestro')
                            ->select('UBPP')
                                ->where('año', $anioPeriodo)
                                ->where('ubpp', $ubpp)
                                ->where('ccpa', $ccpa)
        ->get();
        if( !$respuestaUbpp->count() ){
            // $respuestaData = ['error' => 'UBPP Y PARTIDA INEXISTENTE EN EL PRESUPUESTO'];
            // return $respuestaData;
        }else{
            // obtenemos el mes a consultar
            $mes = substr($request->mes, -2);
            $impCompro = 0;
            $mes < 10 ? $mes = substr($request->mes, -1): 0;
            // obtenemos la solvencia
            $solvencia = $this->validaImp( $ubpp, $ccpa,  $mes, $anioPeriodo );
            if( (($ccpa > 1000 && $ccpa < 2000) || ($ccpa > 7000)) && $ccpa != 9102 ){
                goto Prosigue;
            }
            if( $request->importe > $solvencia ){ 
                $respuestaData = ['error' => 'No existe solvencia'];
                return $respuestaData;
            }
            // si la vista es pagados consultamos PE, si es ejercidos PC
            $request->vista == 'ejercidos' || $request->vista == 'devengado' ? $selectPresup = 'PC' : $selectPresup = 'PE';
            // consultamos PresupMaestro
            $respuestaUbpp = DB::table('PresupMaestro')
                                    ->select(
                                        DB::raw( $selectPresup.$mes.' as ImpCompro'),
                                    )
                                    ->where('año', $anioPeriodo)
                                    ->where('ubpp', $ubpp)
                                    ->where('ccpa', $ccpa)
            ->get();
            !$respuestaUbpp->count() ? $impCompro = 0 : $impCompro = $respuestaUbpp[0]->ImpCompro;
            if(  $request->importe > $impCompro ){
                // si la vista es pagados consultamos no sumamos PD ni PP, si es ejercidos PC
                $request->vista == 'ejercidos' ? $selectPresupRaw = 'PA'.$mes.' - (PE'.$mes.' + PC'.$mes.' ) AS suma ' : $selectPresupRaw = 'PA'.$mes.' - (PE'.$mes.' + PC'.$mes.' + PD'.$mes.'+ PP'.$mes.') AS suma ' ;
                // consultamos presupmaestro
                $respuestaSuma = DB::table('PresupMaestro')
                                                    ->select(
                                                            DB::raw( $selectPresupRaw ),
                                                        )
                                                        ->where('UBPP', $ubpp )
                                                        ->where('CCPA', $ccpa )
                                                        ->where('AÑO', $anioPeriodo )
                ->get();
                !$respuestaSuma->count() ? $respuestaSuma = 0 : $respuestaSuma = $respuestaSuma[0]->suma;
                if(  $request->importe > ($impCompro + $respuestaSuma) ){
                    $respuestaData = ['error' => 'No existe solvencia [103]'];
                    return $respuestaData;
                }
            }
            Prosigue:
            $nuevaSolvencia = $solvencia - $request->importe;
            $nuevaSolvencia > 0 ? $nuevaSolvencia = '<span class="text-primary">'.$nuevaSolvencia.'</span>' : $nuevaSolvencia = '<span class="text-danger">'.$nuevaSolvencia.'</span>';
            // llenamos nuestra tabla
            $tabla = $tabla."<tr>
                                <td><button type='submit' id='editar' class='btn btn-light-primary font-weight-bold'>Editar</button><br></br><button type='submit' id='eliminar' class='btn btn-light-primary font-weight-bold'>Eliminar</button></td>
                                <td>".$ubpp."</td>
                                <td>".$ccpa."</td>
                                <td>".$denominacion."</td>
                                <td>".$solvencia."</td>
                                <td>".$request->importe."</td>
                                <td>".$nuevaSolvencia."</td>
                                <td>".$request->cons."</td>
                                <td>".$mes."</td>
                                <td></td>
                                <td>".$request->observaciones."</td>
                                <td>".$request->folio."</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>";
        }
        $respuestaData = ['error' => '','tabla' =>  $tabla];
        return $respuestaData;
    }

    public function guardarFolioEjercidos( Request $request ){
        ini_set('max_execution_time', 600);
        $tipo           = 0;
        $selectPresup   = '';
        $updatePresup   = '';
        $nombreUsuario  = '';
        $anioPeriodo    = $request->session()->get('anioPeriodo');
        // verificamos la vista recibida
        switch ($request->vista) {
            case 'pagados':
                $tipo = 6;
                $selectPresup   = 'Pag';
                $updatePresup   = 'Pag';
                break;
                
            case 'ejercidos':
                $tipo = 1;
                $selectPresup   = 'Eje';
                $updatePresup   = 'Eje';
                break;
            
            case 'devengado':
                $tipo = 5;
                $selectPresup   = 'Eje';
                $updatePresup   = 'Dev';
                break;
            case 'compromisos':
                $tipo = 2;
                $selectPresup   = 'COM';
                $updatePresup   = 'COM';
                break;
        }
        // recorremos los datos recibidos
        foreach ($request->table as $key => $value) {
            // obtenemos el folio
            $folio = DB::table('PresupFolios')
                            ->select( $selectPresup.' as Pag' )
                            ->where('año', $anioPeriodo)
            ->get();
            $folio = $folio[0]->Pag;
            $folioUpdate = 0;
            // actualizamos pag -- validar
            if($request->folio != $folio){
                $nuevoFolio = $folio + 1;
                $request->vista == 'devengado' ? $folioUpdate = $folio : $folioUpdate = $nuevoFolio ;
                $affected = DB::table('PresupFolios')
                ->where('año', $anioPeriodo )
                ->update([$updatePresup => $folioUpdate]);
            }else{
                $nuevoFolio = $request->folio;
            }

            $fila               = explode("_", $value);
            $fila[11] == '' || null ? $fila[11] = 0 : intval( $fila[11] );
            $fila[12] == '' || null ? $fila[12] = 0 : intval( $fila[12] );
            $fila[13] == '' || null ? $fila[13] = 0 : intval( $fila[13] );
            $fila[14] == '' || null ? $fila[14] = 0 : intval( $fila[14] );
            $fila[15] == '' || null ? $fila[15] = 0 : intval( $fila[15] );
            $ubpp               = $fila[1];
            $ccpa               = $fila[2];
            $importe            = $fila[5];
            $cons               = $fila[7];
            $mes                = $fila[8];
            $concepto           = $fila[9];
            $descripcionGasto   = $fila[10];
            $folEg              = $fila[11];
            $Fol_Com            = $request->vista == 'compromisos' ? $Fol_Com = ' ' : $Fol_Com = $fila[12];
            $Cons_Com           = $request->vista == 'compromisos' ? $Cons_Com = ' ' : $Cons_Com = $fila[13];
            $Mes_Com            = $request->vista == 'compromisos' ? $Mes_Com = ' ' : $Mes_Com = $fila[14];
            $Imp_Com            = $request->vista == 'compromisos' ? $Imp_Com = ' ' : $Imp_Com = $fila[15];
            // obtenemos captu
            // $loginName = DB::table('PresupMov')
            //     ->join('Partidas', function ($join) {
            //         $join->on('PresupMov.CCPA', '=', 'Partidas.CCPA');
            //         $join->on('PresupMov.Año', '=', 'Partidas.Año');
            //     })
            //     ->select( 'PresupMov.CAPTU' )
            //         ->where( 'PresupMov.Tipo', 2 )
            //         ->where( 'PresupMov.Año', $anioPeriodo )
            //         ->where( 'PresupMov.Folio', $folio )
            // ->get();
            // if( $loginName->count() ){
            //     // obtenemos el loging name
            //     $captu = DB::table('Usuarios')
            //         ->select('Nombre')
            //         ->where('Login_Name', $loginName[0]->CAPTU)
            //     ->get();
            //     $nombreUsuario = substr($captu[0]->Nombre, 0, 14);
            // }else{
            //     $nombreUsuario = 'No establecido';
            // }

            if (session()->exists('UserAdmin')){   
                $nombreUsuario = session()->get('UserAdmin');
            }else{
                if (session()->exists('User')){
                    $nombreUsuario = session()->get('User');
                }
            }
            
            // eliminamos el folio recibido
                DB::table('PresupMov')
                        ->where('Folio', $folio)
                        ->where( 'año' , $anioPeriodo)
                        ->where( 'Tipo' , $tipo)
            ->delete();
            
            // insertamos los datos de la tabla
            DB::table('PresupMov')->insert([
                'FOLIO'         => $nuevoFolio,
                'FECHA'         => date("Ymd"),
                'TIPO'          => $tipo,
                'UBPP'          => $ubpp,
                'CCPA'          => $ccpa,
                'IMPORTE'       => $importe,
                'CONS'          => $cons,
                'MES'           => $mes,
                'Concepto'      => $concepto,
                'Observaciones' => $descripcionGasto,
                'Requisicion'   => $folEg,
                'CAPTU'         => $nombreUsuario,
                'Año'           => $anioPeriodo,
                'Fol_Com'       => $Fol_Com,
                'Cons_Com'      => $Cons_Com,
                'Mes_Com'       => $Mes_Com,
                'Imp_Com'       => $Imp_Com
            ]);
        }
        $respuestaData = ['error' => '', 'folio' => $nuevoFolio, 'mensaje' => 'Guardado exitoso'];
        return $respuestaData;
    }

    public function nombreCapturista(Request $request){
        $captu = DB::select("Select Nombre From Usuarios Where Login_Name = (SELECT top 1 PresupMov.CAPTU From PresupMov, Partidas
        Where PresupMov.CCPA = Partidas.CCPA AND PresupMov.Año = Partidas.Año AND 
        PresupMov.Tipo = 1 AND PresupMov.Año = ".session()->get('anioPeriodo')." AND PresupMov.Folio = ".$request->folio.") ");
        if($captu[0]->Nombre != ""){
            return $captu[0]->Nombre;
        }else{
            if (session()->exists('IpeAdminKey')){   
                $captu = session()->get('IpeAdminKey');
            }else{
                if (session()->exists('IpeKey')){
                    $captu = session()->get('Ipekey');
                }
            }
            return $captu;
        }
    }

    public function valSol1(Request $request){
        $solvencia = DB::table('PresupMaestro')
        ->select(DB::raw('PC'.$request->mes.' AS ImpCompro'))
        ->where('UBPP', $request->ubpp)
        ->where('CCPA', $request->ccpa)
        ->where('Año', session()->get('anioPeriodo'))
        ->get();

        return $solvencia;
    }

    public function compromisos(Request $request){
        $numerovista = 269;
        $UBPP = DB::table('PresupUBPP')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('UBPP', 'ASC')
        ->get();
        $DescripUBPP = DB::table('PresupUBPP')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('Descrip', 'ASC')
        ->get();
        $CCPA = DB::table('Partidas')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('CCPA', 'ASC')
        ->get();
        $DescripCCPA = DB::table('Partidas')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('Descripción', 'ASC')
        ->get();
        $permisos = $request->session()->get('permisos');
        if (in_array($numerovista, $permisos)) {
            return view('pages.ControlPresupuestal.Centros-de-trabajo.compromisos', compact('UBPP', 'DescripUBPP', 'CCPA', 'DescripCCPA'));
        }else{
          return view('pages.contenido-restringido');
        }
    }

    public function buscaFolioCompromiso(Request $request){
        $folios = DB::table('PresupMov')
        ->select('FOLIO', 'CONS', 'UBPP', 'CCPA', 'IMPORTE', 'ESTADO', 'FECHA', 'MES', 'Año', 'Requisicion', 'Concepto', 'Observaciones')
        ->where('FOLIO', $request->folio)
        ->where('TIPO', 2)
        ->where('Año', session()->get('anioPeriodo'));

        $join = DB::table('Partidas')
        ->select('folios.FOLIO', 'folios.CONS', 'folios.UBPP', 'folios.CCPA', 'Partidas.Descripción', 'folios.IMPORTE', 'folios.ESTADO', 
        'folios.FECHA', 'folios.MES', 'folios.Año', 'folios.Requisicion', 'folios.Concepto', 'folios.Observaciones')
        ->joinSub($folios, 'folios', function ($join) {
            $join->on('Partidas.CCPA', '=', 'folios.CCPA', 'AND', 'Partidas.Año', '=', 'folios.Año');
        })
        ->distinct()
        ->orderBy('folios.CONS', 'ASC')
        ->get();

        return $join;
    }

    public function solvenciaCompromiso(Request $request){
        $solvencia = DB::table('PresupMaestro')
        ->select(DB::raw('PA'.$request->mes.' AS PA'))
        ->where('UBPP', $request->ubpp)
        ->where('CCPA', $request->ccpa)
        ->where('Año', session()->get('anioPeriodo'))
        ->get();

        return $solvencia;
    }

    public function validaImp( $UBPP, $CCPA, $mes, $anioPeriodo  ): float{
        $respuestaSolvencia = DB::table('PresupMaestro')
            ->select(DB::raw('PA'.$mes.' - (PE'.$mes.' + PC'.$mes.' + PD'.$mes.' + PP'.$mes.') AS Solv '))
            ->where('UBPP', $UBPP )
            ->where('CCPA', $CCPA )
            ->where('AÑO', session()->get('anioPeriodo'))
            ->get();
        $respuestaSolvencia->count() ? 0 : $respuestaSolvencia = 0;
        return $respuestaSolvencia[0]->Solv;
    }

    public function validaImp2(Request $request){
        $Solve = DB::table('PresupMaestro')
            ->select(DB::raw('PA'.$request->mes.' - (PE'.$request->mes.' + PC'.$request->mes.' + PD'.$request->mes.' + PP'.$request->mes.') AS Solv '))
            ->where('UBPP', $request->UBPP )
            ->where('CCPA', $request->CCPA )
            ->where('AÑO', session()->get('anioPeriodo'))
            ->get();
        $Solve->count() ? 0 : $Sol = 0;
        return $Solve[0]->Solv;
    }

    public function guardarFolioCompromiso(Request $request){
        $Com = DB::table('PresupFolios')->select('Com')
        ->where('Año', session()->get('anioPeriodo'))
        ->get();
        if (!empty($Com)){
            $nuevoCom=$Com[0]->Com+1;
            DB::table('PresupFolios')
            ->where('Año', session()->get('anioPeriodo'))
            ->update(['Com' => $nuevoCom]);
        }
        $dato = DB::table('PresupMov')
        ->select(DB::raw('MAX(FOLIO) as Folio'))
        ->where('Tipo', 2)
        ->where('Año', session()->get('anioPeriodo'))
        ->get();

        $folio=$dato[0]->Folio+1;
        DB::table('PresupMov')
        ->where('Folio', '=', $folio)
        ->where('Tipo', '=', 2)
        ->where('Año', session()->get('anioPeriodo'))
        ->delete();
  
        $Date = date("Y-m-d");

        $nombreUsuario = "";
        if (session()->exists('User')){
            $nombreUsuario = session()->get('User');
            $Año = session()->get('anioPeriodo');
        }else{
            if (session()->exists('UserAdmin')){
                $nombreUsuario = session()->get('UserAdmin');
                $Año = session()->get('anioPeriodo');
            }
        }

        $arrayTabla = $request->datos;
        if (!empty($arrayTabla) && count($arrayTabla)>0){
            foreach ($arrayTabla as $rowTabla){
                DB::table('PresupMov')->insert([
                'FOLIO'=>$folio,
                'FECHA'=>$Date,
                'TIPO'=>2,
                'UBPP'=>$rowTabla["UBPP"],
                'CCPA' =>$rowTabla["CCPA"],
                'IMPORTE'=>$rowTabla["IMPORTE"],
                'CONS'=>$rowTabla["CONS"],
                'MES' =>$rowTabla["MES"],
                'Concepto'=>$rowTabla["Concepto"],
                'Observaciones' =>$rowTabla["Observaciones"],
                'Requisicion'=>$rowTabla["Requisicion"],
                'Captu' =>$nombreUsuario,
                'Año' =>session()->get('anioPeriodo')]);
            }
        }

        $arrayReturn["Folio"] = $folio;
        $arrayReturn["Res"] = true;
    
        return $arrayReturn;
    }

    public function transferencias(Request $request){
        $numerovista = 270;
        $permisos = $request->session()->get('permisos');
        $partidas = DB::table('Partidas')->select('*')
        ->where('año', session()->get('anioPeriodo'))
        ->orderBy('Descripción', 'asc')
        ->get();
        $UBPP = DB::table('PresupUBPP')->select('*')
        ->where('Año', session()->get('anioPeriodo'))
        ->orderBy('Descrip', 'asc')
        ->get();
        if (in_array($numerovista, $permisos)) {
            return view('pages.ControlPresupuestal.Centros-de-trabajo.transferencias', compact('partidas', 'UBPP'));
        }else{
          return view('pages.contenido-restringido');
        }
    }

    public function buscaFolio(Request $request){
        $folios = DB::table('PresupTrans')->select('*')
        ->where('Folio', $request->folio)
        ->where('Cve', 1)
        ->where('Año', session()->get('anioPeriodo'));

        $join = DB::table('partidas')
        ->select('Descripción','folios.*')
        ->joinSub($folios, 'folios', function ($join) {
            $join->on('Partidas.CCPA', '=', 'folios.CCPA');
        })
        ->where('Partidas.Año', session()->get('anioPeriodo'))
        ->orderBy('folios.Cons', 'asc')
        ->get();

        return $join;
    }

    public function traerDescripcionPartidas(Request $request){
        $dato = DB::table('Partidas')->select('*')
        ->where('CCPA', $request->CCPA)
        ->where('Año', session()->get('anioPeriodo'))
        ->get();
        return $dato;
    }

    public function buscarUBPPCCPA(Request $request){
        $dato = DB::table('PresupMaestro')->select('*')
        ->where('UBPP', $request->UBPP)
        ->where('CCPA', $request->CCPA)
        ->where('Año', session()->get('anioPeriodo'))
        ->get();
        return $dato;
    }

    public function maxFolio(Request $request){
        $tra = DB::table('PresupFolios')->select('Tra')
        ->where('Año', session()->get('anioPeriodo'))
        ->get();
        if (!empty($tra)){
            $nuevoTra=$tra[0]->Tra+1;
            DB::table('PresupFolios')
            ->where('Año', session()->get('anioPeriodo'))
            ->update(['Tra' => $nuevoTra]);
        }
        $dato = DB::table('PresupTrans')
        ->select(DB::raw('MAX(FOLIO) as Folio'))
        ->where('Cve', 1)
        ->where('Año', session()->get('anioPeriodo'))
        ->get();

        $folio=$dato[0]->Folio+1;
        DB::table('PresupTrans')
        ->where('Folio', '=', $folio)
        ->where('Cve', '=', 1)
        ->where('Año', session()->get('anioPeriodo'))
        ->delete();
  
        $Date = date("Y-m-d");

        $nombreUsuario = "";
        if (session()->exists('IpeAdminKey')){
            $nombreUsuario = session()->get('IpeAdminKey');
            $Año = session()->get('anioPeriodo');
        }else{
            if (session()->exists('IpeKey')){
                $nombreUsuario = session()->get('Ipekey');
                $Año = session()->get('anioPeriodo');
            }
        }

        $arrayTabla = $request->datos;
        if (!empty($arrayTabla) && count($arrayTabla)>0){
            foreach ($arrayTabla as $rowTabla){
                DB::table('PresupTrans')->insert([
                'FOLIO'=>$folio,
                'CONS'=>$rowTabla["CONS"],
                'UBPP'=>$rowTabla["UBPP"],
                'CCPA'=>$rowTabla["CCPA"],
                'MES' =>$rowTabla["MES"],
                'IMPORTE'=>$rowTabla["IMPORTE"],
                'TIPO'=>$rowTabla["TIPO"],
                'CVE' => 1,
                'FEC_MOV'=>$Date,
                'CAPTU' =>$nombreUsuario,
                'Año'=>$Año,
                'Observacion' =>$rowTabla["Observacion"],
                'UBPPSol' =>$rowTabla["UBPP"]
                ]);
            }
        }

        $arrayReturn["Folio"] = $folio;
        $arrayReturn["Res"] = true;
    
        return $arrayReturn;
    }

    public function solvencia(Request $request){
        $numerovista = 670;
        $permisos = $request->session()->get('permisos');
        $partidas = DB::table('Partidas')->select('*')
        ->where('año', session()->get('anioPeriodo'))
        ->orderBy('Descripción', 'asc')
        ->get();
        if (in_array($numerovista, $permisos)) {
            return view('pages.ControlPresupuestal.Centros-de-trabajo.solvencia', compact('partidas'));
        }else{
          return view('pages.contenido-restringido');
        }
    }

    public function busca(Request $request){
        $datos = DB::table('PresupMaestro')->select('*')
        ->where('UBPP', $request->UBPP)
        ->where('CCPA', $request->CCPA)
        ->where('año', session()->get('anioPeriodo'))
        ->orderBy('ubpp', 'asc')
        ->get();
        return $datos;
    }

    public function contra(Request $request){
        $usuario = DB::table('PresupUBPP')->select('*')
        ->where('Login', $request->login)
        ->where('Año', session()->get('anioPeriodo'))
        ->get();
        return $usuario;
    }

    public function presupuestoModificado(Request $request){
        $numerovista = 918;
        $permisos = $request->session()->get('permisos');
        if (in_array($numerovista, $permisos)) {
            return view('pages.ControlPresupuestal.Centros-de-trabajo.presupuesto-modificado');
        }else{
          return view('pages.contenido-restringido');
        }
    }

}
