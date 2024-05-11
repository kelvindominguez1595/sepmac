@extends('voyager::master')
@section('page_header')
    <h1 class="page-title">
        <i class="voyager-company"></i> Presupuestos
    </h1>
@stop
@section('css')
    <style>
        .btn.btn-primary {
            text-decoration: none;
        }

        .btn.btn-info {
            text-decoration: none;
        }

        .btn.btn-danger {
            text-decoration: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"
        integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@stop
@section('content')
    <div class="page-content container-fluid">


        <x-link href="{{ route('presupuestos.create') }}">Crear presupuesto</x-link>


        <x-table>
            @slot('header')
                <th>Nombre</th>
                <th>Opciones</th>
            @endslot
            @slot('body')
                @foreach ($presupuestos as $presupuesto)
                    <tr>
                        <td>{{ $presupuesto->nombre }} - {{ date('Y', strtotime($presupuesto->fecha_inicio)) }}</td>
                        <td>
                            <x-link class="btn-info" title="Agregar partidas"
                                href="{{ route('partida.create', ['uuid' => $presupuesto]) }}" role="button">
                                <i class="fas fa-save"></i>
                                Nueva Partida
                            </x-link>

                            <x-link class="btn-info" href="{{ route('presupuestos.edit', $presupuesto) }}" role="button"><i
                                    class="fas fa-edit"></i> Editar
                                Presupuesto</x-link>
                            <x-button class="btn-danger" value="{{ $presupuesto->id }}" id="btnBorrar" role="button"><i
                                    class="fas fa-trash"></i>
                                Borrar Presupuesto</x-button>
                        </td>
                    </tr>
                @endforeach
            @endslot
        </x-table>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });
            $(document).on("click", "#btnBorrar", function() {
                let id = $(this).val();

                Swal.fire({
                    title: "¿Está segur@?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "¡Sí, bórralo!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                                url: "/admin/presupuestos/" + id,
                                method: "DELETE",
                                dataType: "JSON",
                            })
                            .done(function(done) {
                                Swal.fire({
                                    title: "¡Borrado!",
                                    text: "Registro ha sido borrado.",
                                    icon: "success",
                                    confirmButtonText: "Aceptar",
                                }).then((result) => {
                                    let {
                                        isConfirmed,
                                        isDismissed,
                                        isDenied
                                    } = result;
                                    if (isConfirmed || isDismissed) {
                                        location.reload();
                                    }
                                });
                            })
                            .fail(function(fail) {
                                Swal.fire({
                                    title: "Oops!",
                                    text: "Ah ocurrido un error inesperado :c",
                                    icon: "error",
                                    confirmButtonText: "Aceptar",
                                });
                            });
                    }
                });
            });

        });
    </script>
@stop
