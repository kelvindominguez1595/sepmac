$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // formulario
    $("#formeditar").on("submit", function (event) {
        // Evitar que el formulario se envíe normalmente
        event.preventDefault();
        console.log("response");
        let tablabody = $("#tbldetalles > tbody tr");
        let preciosId = [];
        let montos = [];
        let detalleid = $("#detalle_id").val();
        let nombre = $("#nombre").val();
        let partida = $("#partida").val();
        let pr = $("#pr").val();
        if (tablabody.length > 0) {
            // Iterar sobre cada bloque de tres filas en la tabla
            tablabody.each(function () {
                let _row = $(this);
                let precioIdl = [];
                let montoGet = [];

                if (_row.find(".preciosid").length > 0) {
                    _row.find(".preciosid").each(function () {
                        preciosId.push($(this).val());
                    });
                }

                if (_row.find(".mesmonto").length > 0) {
                    _row.find(".mesmonto").each(function () {
                        let monto = $(this).val() == "" ? 0 : $(this).val();
                        montos.push(monto);
                    });
                }
            });

            if (nombre.length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "El campo detalle es requerido",
                    icon: "error",
                    confirmButtonText: "Aceptar",
                });
            }
            console.log(preciosId, montos);
            let data = { nombre, pr, partida, detalleid, preciosId, montos };

            $.ajax({
                url: "/admin/partida-detalles/" + detalleid,
                method: "PUT",
                dataType: "JSON",
                data: data,
            })
                .done(function (done) {
                    let { presupuesto, partida } = done;
                    Swal.fire({
                        title: "Exitos!",
                        text: "Datos actualizados correctamente",
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    }).then((result) => {
                        let { isConfirmed, isDismissed, isDenied } = result;
                        if (isConfirmed || isDismissed) {
                            location.href = `../${partida}?presupuesto=${presupuesto}`;
                        }
                    });
                })
                .fail(function (fail) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Ah ocurrido un error inesperado :c",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    });
                });
        } else {
            Swal.fire({
                title: "Error!",
                text: "Por lo menos debe de existir un detalle",
                icon: "error",
                confirmButtonText: "Aceptar",
            });
        }
    });
});
