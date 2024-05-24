$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#btnadd").on("click", function () {
        let meses = [
            "ENERO",
            "FEBRERO",
            "MARZO",
            "ABRIL",
            "MAYO",
            "JUNIO",
            "JULIO",
            "AGOSTO",
            "SEPTIEMBRE",
            "OCTUBRE",
            "NOVIEMBRE",
            "DICIEMBRE",
        ];
        let table = $("#tbldetalles tbody");
        let html = `
            <tr>
                <td class="bg-info">Detalle:</td>
                <td class="bg-info" colspan="10">
                    <input type="text" name="detalle[]" id="detalle"
                        class="form-control detalle" autocomplete="false"/>
                </td>
                <td class="text-center bg-info">
                    <button type="button" class="btn btn-danger borrar-fila"
                        title="Borrar detalle">
                        <i class="fa fa-trash"></i></button>
                </td>
            </tr>
             <tr>
        `;
        meses.map((e, i) => {
            html += `
                <td>${e} <input type="hidden" class="mestitle" name="${e}${i}[]" value="${e}" autocomplete="false"/></td>
            `;
        });
        html += `</tr><tr>`;
        meses.map((e, i) => {
            html += `
                <td >
                    <input type="number" name="mes${i}[]" id="mes${i}" step="any"
                    class="form-control mesmonto" value="0" min="0" autocomplete="false" />
                </td>
            `;
        });
        html += `</tr>`;
        table.append(html);
    });

    $("#tbldetalles").on("click", ".borrar-fila", function () {
        // Obtener la fila padre del botón
        var filaPadre = $(this).closest("tr");

        // Eliminar las dos siguientes filas
        filaPadre.next("tr").remove();
        filaPadre.next("tr").remove();

        // Eliminar la fila en la que se encuentra el botón
        filaPadre.remove();
    });
    // formulario
    $("#fm-detalles").on("submit", function (event) {
        // Evitar que el formulario se envíe normalmente
        event.preventDefault();

        let tablabody = $("#tbldetalles > tbody tr");
        let titles = [];
        let meses = [];
        let montos = [];
        if (tablabody.length > 0) {
            // Iterar sobre cada bloque de tres filas en la tabla
            tablabody.each(function () {
                let _row = $(this);
                let mesesGet = [];
                let montoGet = [];
                // Obtener el valor del campo de la primera fila (único campo)
                let _firstRowValue = _row.find(".detalle").val();
                if (_firstRowValue != undefined) {
                    titles.push(_firstRowValue);
                }
                if (_row.find(".mestitle").length > 0) {
                    _row.find(".mestitle").each(function () {
                        mesesGet.push($(this).val());
                    });
                    meses.push(mesesGet);
                }

                if (_row.find(".mesmonto").length > 0) {
                    _row.find(".mesmonto").each(function () {
                        let monto = $(this).val() == "" ? 0 : $(this).val();
                        montoGet.push(monto);
                    });
                    montos.push(montoGet);
                }
            });

            var index = titles.findIndex((elemento) => elemento === "");
            if (index !== -1) {
                Swal.fire({
                    title: "Error!",
                    text: "La descripcion del detalle es requerido",
                    icon: "error",
                    confirmButtonText: "Aceptar",
                });
                return;
            }
            let presupuesto_id = $("#presupuesto_id").val();
            let pr = $("#pr").val();
            let volver = $("#volver").val();

            let data = { presupuesto_id, volver, pr, titles, meses, montos };
            $.ajax({
                url: "/admin/partida-detalles",
                method: "POST",
                dataType: "JSON",
                data: data,
            })
                .done(function (done) {
                    Swal.fire({
                        title: "Exitos!",
                        text: "Datos registrados correctamente",
                        icon: "success",
                        confirmButtonText: "Aceptar y Volver a las partidas",
                        cancelButtonText: "Seguir agregando mas detalles",
                        showCancelButton: true,
                    }).then((result) => {
                        let { isConfirmed, isDismissed, isDenied } = result;
                        if (isConfirmed) {
                            if (done.volver == "volver") {
                                location.href = `../ingresar-partidas/${done.uuid}/edit`;
                            } else {
                                location.href = `../partida/create?uuid=${done.uuid}`;
                            }
                        }
                        if (isDismissed) {
                            tablabody.empty();
                            location.reload();
                        }
                    });
                })
                .fail(function (fail) {
                    console.log("Res", fail);
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
