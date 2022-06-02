<?php

use Illuminate\Support\Facades\Route;

// control presupuestal / archivo
Route::get('/control-presupuestal', 'ControlPresupuestal\ControlPresupuestalController@homeControlPresupuestal')->name('control-presupuestal');

Route::get('/ActualizarFolios', 'ControlPresupuestal\MantenimientoController@ActualizarFolios');
Route::get('/CrearNuevoAnioPresupuestal', 'ControlPresupuestal\MantenimientoController@CrearNuevoAnioPresupuestal');

Route::get('/control-presupuestal/NomIPEArmaImportes', 'ControlPresupuestal\ArchivoController@NomIPEArmaImportes')->name('NomIPEArmaImportes');
Route::get('/control-presupuestal/NomIPEProcesar', 'ControlPresupuestal\ArchivoController@NomIPEProcesar')->name('NomIPEProcesar');
Route::post('/control-presupuestal/NomIPEGuardar', 'ControlPresupuestal\ArchivoController@NomIPEGuardar')->name('NomIPEGuardar');

// CP > Cancelaciones > Pagados
Route::get('/control-presupuestal/guardarPagado', 'ControlPresupuestal\ArchivoController@guardarPagado')->name('guardarPagado');
Route::get('/control-presupuestal/eliminarPagado', 'ControlPresupuestal\ArchivoController@eliminarPagado')->name('eliminarPagado');
Route::get('/control-presupuestal/buscarPagado', 'ControlPresupuestal\ArchivoController@buscarPagado')->name('buscarPagado');
//CP > Cancelaciones > Compromisos
Route::get('/control-presupuestal/guardarPagadoCompromisos', 'ControlPresupuestal\ArchivoController@guardarPagadoCompromisos')->name('guardarPagadoCompromisos');
Route::get('/control-presupuestal/eliminarPagadoCompromisos', 'ControlPresupuestal\ArchivoController@eliminarPagadoCompromisos')->name('eliminarPagadoCompromisos');
Route::get('/control-presupuestal/buscarPagadoCompromisos', 'ControlPresupuestal\ArchivoController@buscarPagadoCompromisos')->name('buscarPagadoCompromisos');
//CP > Cancelaciones > Ejercidos
Route::get('/control-presupuestal/guardarEjercido', 'ControlPresupuestal\ArchivoController@guardarEjercido')->name('guardarEjercido');
Route::get('/control-presupuestal/eliminarEjercido', 'ControlPresupuestal\ArchivoController@eliminarEjercido')->name('eliminarEjercido');
Route::get('/control-presupuestal/buscarEjercido', 'ControlPresupuestal\ArchivoController@buscarEjercido')->name('buscarEjercido');
//CP > Reducciones Colectivas
Route::get('/control-presupuestal/verificaSolvencia', 'ControlPresupuestal\ArchivoController@verificaSolvencia')->name('verificaSolvencia');
Route::get('/control-presupuestal/guardarCmd', 'ControlPresupuestal\ArchivoController@guardarCmd')->name('guardarCmd');
Route::get('/control-presupuestal/eliminaCmd', 'ControlPresupuestal\ArchivoController@eliminaCmd')->name('eliminaCmd');


Route::get('/getAniosPresupuestales', 'ControlPresupuestal\MantenimientoController@getAniosPresupuestales');
Route::get('/borraAnioPresupuestal', 'ControlPresupuestal\MantenimientoController@borraAnioPresupuestal');
//Route::get('/miJqueryAjax/{id}', 'TestController@ajax');

Route::get('/control-presupuestal/modificar-presupuestos', 'ControlPresupuestal\ArchivoController@mofificarPresupuestos')->name('modificar-presupuestos');
Route::get('/control-presupuestal/movimientos-pagados', 'ControlPresupuestal\ArchivoController@pagados')->name('movimientos-pagados');
Route::get('/control-presupuestal/movimientos-ejercidos', 'ControlPresupuestal\ArchivoController@ejercidos')->name('movimientos-ejercidos');
Route::get('/control-presupuestal/movimientos-devengados', 'ControlPresupuestal\ArchivoController@devengados')->name('movimientos-devengados');
Route::get('/control-presupuestal/movimientos-compromisos', 'ControlPresupuestal\ArchivoController@compromisos')->name('movimientos-compromisos');
Route::get('/control-presupuestal/movimientos-transferencias', 'ControlPresupuestal\ArchivoController@transferencias')->name('movimientos-transferencias');
Route::get('/control-presupuestal/movimientos-transferencias-automaticas', 'ControlPresupuestal\ArchivoController@transferenciasAutomaticas')->name('movimientos-transferencias-automaticas');
Route::get('/control-presupuestal/movimientos-distribuciones', 'ControlPresupuestal\ArchivoController@distribuciones')->name('movimientos-distribuciones');
Route::get('/control-presupuestal/movimientos-nomina-IPE', 'ControlPresupuestal\ArchivoController@nominaIPE')->name('movimientos-nomina-IPE');
Route::get('/control-presupuestal/movimientos-nomina-IPE-disco', 'ControlPresupuestal\ArchivoController@nominaIPEDisco')->name('movimientos-nomina-IPE-disco');
Route::get('/control-presupuestal/cancelaciones-pagados', 'ControlPresupuestal\ArchivoController@cancelacionesPagados')->name('cancelaciones-pagados');
Route::get('/control-presupuestal/cancelaciones-ejercidos', 'ControlPresupuestal\ArchivoController@cancelacionesEjercidos')->name('cancelaciones-ejercidos');
Route::get('/control-presupuestal/cancelaciones-compromisos', 'ControlPresupuestal\ArchivoController@cancelacionesCompromisos')->name('cancelaciones-compromisos');
Route::get('/control-presupuestal/afectaciones-liquidas', 'ControlPresupuestal\ArchivoController@afectacionesLiquidas')->name('afectaciones-liquidas');
Route::get('/control-presupuestal/reducciones-colectivas', 'ControlPresupuestal\ArchivoController@reduccionesColectivas')->name('reducciones-colectivas');
Route::get('/control-presupuestal/reducciones-capituo-1000', 'ControlPresupuestal\ArchivoController@reduccionesCapituo1000')->name('reducciones-capituo-1000');
Route::get('buscaFolioMovimientos', 'ControlPresupuestal\ArchivoController@buscaFolioMovimientos');
Route::get('buscaFolioImportMovimientos', 'ControlPresupuestal\ArchivoController@buscaFolioImportMovimientos');
Route::get('agregaUBPPMovimientos', 'ControlPresupuestal\ArchivoController@agregaUBPPMovimientos');
Route::get('agregarMovimientos', 'ControlPresupuestal\ArchivoController@agregarMovimientos');
Route::get('guardarTblMovimientos', 'ControlPresupuestal\ArchivoController@guardarTblMovimientos');
Route::get('aplicarMovimientos', 'ControlPresupuestal\ArchivoController@aplicarMovimientos');
Route::get('aplicarMovEjercidos', 'ControlPresupuestal\ArchivoController@aplicarMovEjercidos');
Route::get('aplicarMovDevengado', 'ControlPresupuestal\ArchivoController@aplicarMovDevengado');
Route::get('agregarMovimientosCompromisos', 'ControlPresupuestal\ArchivoController@agregarMovimientosCompromisos');
Route::get('aplicarMovimientosCompromiso', 'ControlPresupuestal\ArchivoController@aplicarMovimientosCompromiso');
Route::get('abrirMovTransferencias', 'ControlPresupuestal\ArchivoController@abrirMovTransferencias');
Route::get('guardarTblTransferencias', 'ControlPresupuestal\ArchivoController@guardarTblTransferencias');
Route::get('aplicarMovimientosTransferencias', 'ControlPresupuestal\ArchivoController@aplicarMovimientosTransferencias');
Route::get('agregarMovimientosTransferencias', 'ControlPresupuestal\ArchivoController@agregarMovimientosTransferencias');
Route::get('procesarMovimientosTransAuto', 'ControlPresupuestal\ArchivoController@procesarMovimientosTransAuto');
Route::get('abrirMovDistribuciones', 'ControlPresupuestal\ArchivoController@abrirMovDistribuciones');
Route::get('guardarTblDistribuciones', 'ControlPresupuestal\ArchivoController@guardarTblDistribuciones');
Route::get('agregarMovimientosDistribuciones', 'ControlPresupuestal\ArchivoController@agregarMovimientosDistribuciones');
Route::get('aplicarMovimientosDistribuciones', 'ControlPresupuestal\ArchivoController@aplicarMovimientosDistribuciones');
// control presupuestal / Catalogos
Route::get('/control-presupuestal/catalogos-partidas', 'ControlPresupuestal\CatalogosController@partidas')->name('catalogos-partidas');
// -----------------

Route::get('/tablaPartidasPre', 'ControlPresupuestal\CatalogosController@tablaPartidas');
Route::get('/borraPrePartidasPre', 'ControlPresupuestal\CatalogosController@borraPrePartida');
Route::get('/PartidasinserUpdatePre', 'ControlPresupuestal\CatalogosController@partidainserUpdatePre');
// -----------------
Route::get('/control-presupuestal/catalogos-catalogo-de-ubpps', 'ControlPresupuestal\CatalogosController@ubpps')->name('catalogo-de-ubpp');
// -----------------
Route::get('/catalogoPreUbps', 'ControlPresupuestal\CatalogosController@catalogoPreUbps');
Route::get('/borraUbp', 'ControlPresupuestal\CatalogosController@borraUbp');
Route::get('/catalogopreubpinsert', 'ControlPresupuestal\CatalogosController@catalogopreubpinsert');
// -----------------

Route::get('/control-presupuestal/catalogos-catalogo-de-programas', 'ControlPresupuestal\CatalogosController@programas')->name('catalogo-de-programas');

// ---------------------------
Route::get('/tablaProgramaPre', 'ControlPresupuestal\CatalogosController@tablaProgramas');
Route::get('/borraPreProgramasPre', 'ControlPresupuestal\CatalogosController@borraPrePrograma');
Route::get('/ProgramasinserUpdatePre', 'ControlPresupuestal\CatalogosController@programainserUpdatePre');

// ---------------------------

Route::get('/control-presupuestal/catalogos-catalogo-de-capitulos', 'ControlPresupuestal\CatalogosController@capitulos')->name('catalogo-de-capitulos');
// -----------------
Route::get('/CapituloPreTabla', 'ControlPresupuestal\CatalogosController@tablaCapitulos');
Route::get('/borraPreCapitulo', 'ControlPresupuestal\CatalogosController@borraPreCapitulo');
Route::get('/capítuloinserUpdatePre', 'ControlPresupuestal\CatalogosController@capítuloinserUpdatePre');
// -----------------

Route::get('/control-presupuestal/catalogos-catalogo-de-conceptos', 'ControlPresupuestal\CatalogosController@conceptos')->name('catalogo-de-conceptos');
// -----------
Route::get('/tablaConceptosPre', 'ControlPresupuestal\CatalogosController@tablaConceptos');
Route::get('/borraPreConceptoPre', 'ControlPresupuestal\CatalogosController@borraPreConcepto');
Route::get('/conceptoinserUpdatePre', 'ControlPresupuestal\CatalogosController@conceptoinserUpdatePre');
// --------------

Route::get('/control-presupuestal/catalogos-catalogo-de-genericos', 'ControlPresupuestal\CatalogosController@genericos')->name('catalogo-de-genericos');
// ---------------------------------------
Route::get('/tablaConGenericoPre', 'ControlPresupuestal\CatalogosController@tablagenericos');
Route::get('/borraPreGenericoPre', 'ControlPresupuestal\CatalogosController@borraPreGenerico');
Route::get('/genericoinserUpdatePre', 'ControlPresupuestal\CatalogosController@genericoinserUpdatePre');
// ---------------------------------------

Route::get('/control-presupuestal/catalogos-catalogos-responsables', 'ControlPresupuestal\CatalogosController@responsables')->name('catalogos-responsables');
// ----------------------------------------------------
Route::get('/tablarResponsablesPre', 'ControlPresupuestal\CatalogosController@tablaResponsables');
Route::get('/responsablesinserUpdatePre', 'ControlPresupuestal\CatalogosController@ResponsablesinserUpdatePre');

// -----------------------------------------------------

// control presupuestal / centros de trabajo
Route::get('/control-presupuestal/centros-de-trabajo-solicitado', 'ControlPresupuestal\CentrosTrabajoController@solicitado')->name('solicitado');
Route::get('/buscarSolicitado','ControlPresupuestal\CentrosTrabajoController@buscarSolicitado');
Route::get('/guardarSolicitado','ControlPresupuestal\CentrosTrabajoController@guardarSolicitado')->name('guardarSolicitado');
Route::get('/control-presupuestal/centros-de-trabajo-ejercidos', 'ControlPresupuestal\CentrosTrabajoController@ejercidos')->name('ejercidos');
Route::get('/buscaFolioEjercidos', 'ControlPresupuestal\CentrosTrabajoController@buscaFolioEjercidos');
Route::get('/agregarTablaEjercidos', 'ControlPresupuestal\CentrosTrabajoController@agregarTablaEjercidos');
Route::get('/nombreCapturista', 'ControlPresupuestal\CentrosTrabajoController@nombreCapturista');
Route::get('/guardarFolioEjercidos','ControlPresupuestal\CentrosTrabajoController@guardarFolioEjercidos')->name('guardarFolioEjercidos');
Route::get('/valSol1', 'ControlPresupuestal\CentrosTrabajoController@valSol1');
Route::get('/control-presupuestal/centros-de-trabajo-compromisos', 'ControlPresupuestal\CentrosTrabajoController@compromisos')->name('compromisos')->middleware('login');
Route::get('/buscaFolioCompromiso', 'ControlPresupuestal\CentrosTrabajoController@buscaFolioCompromiso');
Route::get('/solvenciaCompromiso', 'ControlPresupuestal\CentrosTrabajoController@solvenciaCompromiso');
Route::get('/validaImp', 'ControlPresupuestal\CentrosTrabajoController@validaImp');
Route::get('/validaImp2', 'ControlPresupuestal\CentrosTrabajoController@validaImp2');
Route::get('/guardarFolioCompromiso','ControlPresupuestal\CentrosTrabajoController@guardarFolioCompromiso')->name('guardarFolioCompromiso');
Route::get('/control-presupuestal/centros-de-trabajo-transferencias', 'ControlPresupuestal\CentrosTrabajoController@transferencias')->name('transferencias');
Route::get('/control-presupuestal/centros-de-trabajo-solvencia', 'ControlPresupuestal\CentrosTrabajoController@solvencia')->name('solvencia');
Route::get('/control-presupuestal/centros-de-trabajo-presupuesto-modificado', 'ControlPresupuestal\CentrosTrabajoController@presupuestoModificado')->name('presupuesto-modificado');
Route::get('/busca', 'ControlPresupuestal\CentrosTrabajoController@busca');
Route::get('/contra', 'ControlPresupuestal\CentrosTrabajoController@contra');
Route::get('/buscaFolio', 'ControlPresupuestal\CentrosTrabajoController@buscaFolio');
Route::get('/traerDescripcion', 'ControlPresupuestal\CentrosTrabajoController@traerDescripcion');
Route::get('/traerDescripcionPartidas', 'ControlPresupuestal\CentrosTrabajoController@traerDescripcionPartidas');
Route::get('/buscarUBPPCCPA', 'ControlPresupuestal\CentrosTrabajoController@buscarUBPPCCPA');
Route::get('/maxFolio','ControlPresupuestal\CentrosTrabajoController@maxFolio')->name('maxFolio');

// control presupuestal / consultas
Route::get('/control-presupuestal/consultas-validacion-tipos-movimiento', 'ControlPresupuestal\ConsultasController@validacionTiposMovimiento')->name('validacion-tipos-movimiento');
Route::get('/control-presupuestal/consultas-conciliacion-con-contabilidad', 'ControlPresupuestal\ConsultasController@conciliacionContabilidad')->name('conciliacion-con-contabilidad');
Route::get('/control-presupuestal/consultas-tipos-de-presupuestos', 'ControlPresupuestal\ConsultasController@tiposPresupuestos')->name('tipos-de-presupuestos');
Route::get('/control-presupuestal/consultas-comparativo', 'ControlPresupuestal\ConsultasController@comparativo')->name('comparativo');
Route::get('/control-presupuestal/consultas-sbc', 'ControlPresupuestal\ConsultasController@sbc')->name('sbc');
Route::get('/control-presupuestal/consultas-formato-REPTRIM', 'ControlPresupuestal\ConsultasController@formatoREPTRIM')->name('formato-REPTRIM');
Route::get('/control-presupuestal/consultas-reportes-armonizacion-contable', 'ControlPresupuestal\ConsultasController@reportesArmonizacionContable')->name('reportes-armonizacion-contable');
Route::get('/control-presupuestal/consultas-reporte-cancelaciones', 'ControlPresupuestal\ConsultasController@reporteCancelaciones')->name('reporte-cancelaciones');
Route::get('/buscaFoliosCancelados', 'ControlPresupuestal\ConsultasController@buscaFoliosCancelados');
Route::get('/foliosCancelados', 'ControlPresupuestal\ConsultasController@foliosCancelados');
Route::get('/control-presupuestal/consultas-proyecciones', 'ControlPresupuestal\ConsultasController@proyecciones')->name('proyecciones');
Route::get('/control-presupuestal/consultas-movimientos-comprometidos', 'ControlPresupuestal\ConsultasController@movComprometidos')->name('consultas-movimientos-comprometidos');
Route::get('/control-presupuestal/consultas-movimientos-devengados', 'ControlPresupuestal\ConsultasController@movDevengados')->name('consultas-movimientos-devengados');
Route::get('/control-presupuestal/consultas-movimientos-ejercidos', 'ControlPresupuestal\ConsultasController@movEjercidos')->name('consultas-movimientos-ejercidos');
Route::get('/control-presupuestal/consultas-movimientos-pagados', 'ControlPresupuestal\ConsultasController@movPagados')->name('consultas-movimientos-pagados');
Route::get('/control-presupuestal/consultas-historico-polizas', 'ControlPresupuestal\ConsultasController@historicoPolizas')->name('historico-polizas');
Route::get('/control-presupuestal/consultas-presupuesto-aprobado', 'ControlPresupuestal\ConsultasController@presupuestoAprobado')->name('presupuesto-aprobado');
Route::get('/control-presupuestal/consultas-movimientos-folio-egreso', 'ControlPresupuestal\ConsultasController@movFolioEgreso')->name('movimientos-folio-egreso');
Route::get('/control-presupuestal/consultas-folio-referencia-compromiso', 'ControlPresupuestal\ConsultasController@folioReferenciaCompromiso')->name('folio-referencia-compromiso');
Route::get('/control-presupuestal/consultas-poliza-resumen', 'ControlPresupuestal\ConsultasController@polizaResumen')->name('poliza-resumen');

// control presupuestal / consultas / Solvencia
Route::get('/control-presupuestal/consultas-solvencia-neta', 'ControlPresupuestal\ConsultasController@neta')->name('solvencia-neta');
Route::get('/control-presupuestal/consultas-solvencia-por-grupos', 'ControlPresupuestal\ConsultasController@porGrupos')->name('solvencia-por-grupos');
Route::get('/subProg', 'ControlPresupuestal\ConsultasController@subProg');
Route::get('/buscarDescripcion', 'ControlPresupuestal\ConsultasController@buscarDescripcion');
Route::get('/control-presupuestal/consultas-solvencia-acumulada', 'ControlPresupuestal\ConsultasController@acumulada')->name('solvencia-acumulada');
Route::get('/control-presupuestal/consultas-solvencia-cifras-de-control', 'ControlPresupuestal\ConsultasController@cifrasControl')->name('solvencia-cifras-de-control');

Route::get('/preVigente', 'ControlPresupuestal\ConsultasController@preVigente');
Route::get('/preComprometido', 'ControlPresupuestal\ConsultasController@preComprometido');
Route::get('/preDevengado', 'ControlPresupuestal\ConsultasController@preDevengado');
Route::get('/preEjercido', 'ControlPresupuestal\ConsultasController@preEjercido');
Route::get('/prePagado', 'ControlPresupuestal\ConsultasController@prePagado');
Route::get('/totales', 'ControlPresupuestal\ConsultasController@totales');

// control presupuestal / consultas / Estado-ejercicio-presupuesto
Route::get('/control-presupuestal/consultas-estado-ejercicio-presupuesto-parte-1', 'ControlPresupuestal\ConsultasController@parte1')->name('estado-ejercicio-presupuesto-parte-1');
Route::get('/control-presupuestal/consultas-estado-ejercicio-presupuesto-parte-2', 'ControlPresupuestal\ConsultasController@parte2')->name('estado-ejercicio-presupuesto-parte-2');

// control presupuestal / mantenimiento
Route::get('/control-presupuestal/matenimiento-cambiar-anio-presupuestal', 'ControlPresupuestal\MantenimientoController@cambiarAnioPresupuestal')->name('cambiar-anio-presupuestal');
Route::get('/control-presupuestal/matenimiento-folios', 'ControlPresupuestal\MantenimientoController@folios')->name('folios');
Route::get('/control-presupuestal/matenimiento-crear-presupuestos', 'ControlPresupuestal\MantenimientoController@crearPresupuestos')->name('crear-presupuestos');
Route::get('/control-presupuestal/matenimiento-borrar-presupuestos', 'ControlPresupuestal\MantenimientoController@borrarPresupuestos')->name('borrar-presupuestos');
Route::get('/control-presupuestal/matenimiento-inicial-a-autorizado', 'ControlPresupuestal\MantenimientoController@inicialAutorizado')->name('inicial-a-autorizado');
Route::get('/control-presupuestal/IniAutProcesar', 'ControlPresupuestal\MantenimientoController@IniAutProcesar')->name('IniAutProcesar');

//control presupuestal / imprimir

Route::get('/control-presupuestal/Reportes-Imprimir', 'ControlPresupuestal\ReportesController@Imprimir')->name('Imprimir');
