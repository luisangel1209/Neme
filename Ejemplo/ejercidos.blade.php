{{-- Extends layout --}}
@extends('layout.base._layout-contenido-control-presupuestal')
{{-- Content --}}
@section('content')
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Row-->
        <div class="row">
            <div class="col-lg-12 col-xxl-12">
                <!--Inicio Card-->
                <div class="card card-custom">
                    <div class="mt-4" style="padding-left: 30px">
                        <a href="{{ route('control-presupuestal') }}">
                            <i class="far fa-arrow-alt-circle-left icon-lg" data-toggle="tooltip" data-placement="bottom" title="Atrás"></i>
                        </a>
                    </div>
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Comprometido del Área</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Search Form-->
                        <div class="mb-7">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Folio</label>
                                    <input type="text" class="form-control" id="folio">
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="col-form-label font-weight-bold">Fecha</label>
                                        <!-- <div class="input-group date"> -->
                                            <input type="text" class="form-control" id="fecha" readonly/>
                                            <!-- <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                            </div> -->
                                        <!-- </div> -->
                                    </div>
                                </div>

                                <div align="center" class="col-lg-2" id="botonContra">
                                    <label class="col-form-label font-weight-bold">Ingresar Contraseña</label>
                                    <button type="button" class="btn btn-primary" onclick="ingresarContra()">Ingresar</button>
                                </div>

                            </div>

                        </div>

                        <!-- <div class="separator separator-dashed my-8"> </div> -->

                        <div class="row">

                            <div class="col-lg-2">
                                <label class="col-form-label font-weight-bold">UBPP</label>
                                <Select class="form-control" id="UBPP">
                                    <option value="">Seleccionar</option>
                                    @foreach ($UBPP as $ubpp)
                                    <option value="{{ $ubpp->UBPP }}">{{ $ubpp->UBPP }}</option>
                                    @endforeach
                                </Select>
                            </div>

                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Descripcion</label>
                                <Select class="form-control" id="UBPPDes">
                                    <option value="">Seleccionar</option>
                                    @foreach ($DescripUBPP as $ubpp)
                                    <option value="{{ $ubpp->UBPP }}">{{ $ubpp->DESCRIP }}</option>
                                    @endforeach
                                </Select>
                            </div>

                            <div class="col-lg-2">
                                <label class="col-form-label font-weight-bold">Partida</label>
                                <Select class="form-control" id="CCPA">
                                    <option value="">Seleccionar</option>
                                    @foreach ($CCPA as $par)
                                    <option value="{{ $par->CCPA }}">{{ $par->CCPA }}</option>
                                    @endforeach
                                </Select>
                            </div>

                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Descripcion</label>
                                <Select class="form-control" id="CCPADes">
                                    <option value="">Seleccionar</option>
                                    @foreach ($DescripCCPA as $par)
                                    <option value="{{ $par->CCPA }}">{{ $par->Descripción }}</option>
                                    @endforeach
                                </Select>
                            </div>

                        </div>

                        <!-- <div class="separator separator-dashed my-12"> </div> -->
                        <br></br>
                        <div class="row">

                            <div class="col-lg-3">
                                <label for="example-date-input" class="col-form-label font-weight-bold">Nombre Mes</label>
                                <select class="form-control" id="nombreMes">
                                    <option value="">Selecciona un mes</option>
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="col-form-label font-weight-bold">Importe</label>
                                <input class="form-control" type="text" id="importe">
                            </div>

                            <div class="col-lg-4">
                                <label class="col-form-label font-weight-bold">Observaciones</label>
                                <textarea class="form-control" id="observaciones" rows="3"></textarea>
                            </div>

                            <div class="col-lg-2">
                                <br></br>
                                <button type="submit" class="btn btn-light-primary font-weight-bold" onclick="agregar()" id="agregar">Agregar</button>
                            </div>

                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-primary boton-nav-tipo font-weight-bolder" id="BNuevo" onclick="Nuevo()">
                                <span class="navi-icon">
                                    <!-- <i class="fas fa-print icon-lg"></i> -->
                                    <i class="fas fa-plus icon-lg"></i>
                                </span>Nuevo
                            </button>
                            <button class="btn btn-primary boton-nav-tipo font-weight-bolder" id="BGuardar" onclick="Guardar()" disabled="false">
                                <span class="navi-icon">
                                    <!-- <i class="fas fa-print icon-lg"></i> -->
                                    <i class="fas fa-save icon-lg"></i>
                                </span>Guardar
                            </button>
                            <button class="btn btn-danger font-weight-bolder" id="BImprimir" onclick="Imprimir()" disabled="false">
                                <span class="navi-icon">
                                    <i class="fas fa-print icon-lg"></i>
                                </span>Imprimir
                            </button>
                        </div>
                        <!-- <div class="row "> -->
                            <!--begin: Datatable-->
                            <!--begin: Selected Rows Group Action Form-->
                        <div class="row">
                            <div class="col-lg-6">
                                <input class="form-control" type="text" id="buscar" placeholder="Buscar..">
                            </div>
                        </div>
                            <br></br>
                            <div class="col-lg-42">
                                <!-- <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div> -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tabla">
                                    <thead>
                                        <tr>
                                        <th></th>
                                        <th>UBPP</th>
                                        <th>CCPA</th>
                                        <th><div style="width: 300px; text-align: center;">Denominación</div></th>
                                        <th>Solvencia</th>
                                        <th>Importe</th>
                                        <th>Nva Solvencia</th>
                                        <th>Consec</th>
                                        <th>Mes</th>
                                        <th>Concepto</th>
                                        <th><div style="width: 300px; text-align: center;">Descripción del gasto</div></th>
                                        <th>Fol Eg</th>
                                        <th>Fol Comp</th>
                                        <th>Cons</th>
                                        <th>Mes</th>
                                        <th>Importe</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaBody">
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end: Datatable-->
                        <!-- </div> -->
                    </div>
                    <!--Fin Card-->
                    <!-- Modal -->
                    <div class="modal fade" data-backdrop="static" id="contra" tabindex="-1" role="dialog" aria-labelledby="contralLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="contraLabel">Introduzca contraseña</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                <input id="miContraseña"  class="form-control" type="password" autocomplete="off"></input>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="Cancelar()">Cerrar</button>
                                <button type="button" class="btn btn-primary" onclick="validarContra()">Aceptar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
</div>
{{-- Scripts --}}
@push('scripts')
<script src="../js/ControlPresupuestal/Centros de Trabajo/ejercidos.js"></script>
@endpush
@endsection
{{-- End Content --}}