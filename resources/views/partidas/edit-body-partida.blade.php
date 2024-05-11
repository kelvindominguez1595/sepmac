@extends('voyager::master')
@section('page_header')
    <h1 class="page-title">
        <i class="voyager-company"></i> Editar Partida
    </h1>
@stop

@section('content')

    <div class="page-content edit-add container-fluid">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <a type="button" class="btn btn-primary" href="{{ route('partida.create', ['uuid' => $presupuesto]) }}">
                    <i class="voyager-angle-left"></i> Volver
                </a>
            </div>
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->

                    <x-form action="{{ route('partida.update', $partida->id) }}" method="POST" isPut="si">

                        <input type="hidden" name="presupuestoid" value="{{ $presupuesto }}">
                        <div class="form-group  col-md-9">
                            <label class="control-label" for="nombre">Presupuesto</label>
                            <input required="" type="text" class="form-control" name="nombre"
                                placeholder="Presupuesto" value="{{ $partida->nombre }}">
                        </div>


                        <div class="form-group  col-md-12">
                            <label class="control-label" for="descripcion">Descripcion</label>
                            <textarea name="descripcion" class="form-control">{!! $partida->descripcion !!}</textarea>

                        </div>



                    </x-form>
                </div>
            </div>
        </div>
    </div>
@stop
