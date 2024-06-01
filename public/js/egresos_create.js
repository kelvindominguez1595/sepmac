$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $("#cuenta_id").select2({
        theme: "bootstrap",
    });
    $("#cuenta_e_id").select2({
        theme: "bootstrap",
    });

    $("#cantidad").keyup(function () {
        calcularmontos();
    });
    $("#monto").keyup(function () {
        calcularmontos();
    });
    $("#cantidad").change(function () {
        calcularmontos();
    });
    $("#monto").change(function () {
        calcularmontos();
    });
    $("#formrealizar_solicitud").submit(function (e) {
        e.preventDefault();

        let frm = $(this).serialize();

        $.ajax({
            url: "/admin/egresos",
            method: "POST",
            dataType: "JSON",
            data: frm,
        })
            .done(function (done) {
                $("#modalegresos").modal("hide");
                Swal.fire({
                    title: "Existos!",
                    text: done.message,
                    icon: "success",
                    confirmButtonText: "Aceptar",
                }).then((result) => {
                    let { isConfirmed, isDismissed, isDenied } = result;
                    if (isConfirmed || isDismissed) {
                        location.reload();
                    }
                });
            })
            .fail(function (fail) {
                $("#modalegresos").modal("hide");
                let { responseJSON } = fail;
                let message = null;
                if (responseJSON && responseJSON.errors) {
                    message = formattedMessage(responseJSON.errors);
                }
                message = responseJSON.message;
                Swal.fire({
                    title: "Oops!",
                    html: message,
                    icon: "error",
                    confirmButtonText: "Aceptar",
                    customClass: {
                        popup: "swal-popup",
                    },
                });
            });
    });
    // para enviar el presupuesto
    $(document).on("click", "#btnBorrar", function () {
        let id = $(this).val();

        Swal.fire({
            title: "¿Está segur@?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Sí, bórralo!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/partida/" + id,
                    method: "DELETE",
                    dataType: "JSON",
                })
                    .done(function (done) {
                        Swal.fire({
                            title: "¡Borrado!",
                            text: "Registro ha sido borrado.",
                            icon: "success",
                            confirmButtonText: "Aceptar",
                        }).then((result) => {
                            let { isConfirmed, isDismissed, isDenied } = result;
                            if (isConfirmed || isDismissed) {
                                location.reload();
                            }
                        });
                    })
                    .fail(function (fail) {
                        let { responseJSON } = fail;
                        let message = null;
                        if (responseJSON && responseJSON.errors) {
                            console.log(responseJSON.errors);
                            message = formattedMessage(responseJSON.errors);
                            console.log(message);
                        }
                        Swal.fire({
                            title: "Oops!",
                            text:
                                message != null
                                    ? message
                                    : "Ah ocurrido un error inesperado :c",
                            icon: "error",
                            confirmButtonText: "Aceptar",
                            customClass: {
                                popup: "swal-popup",
                            },
                        });
                    });
            }
        });
    });
});
formattedMessage = (error) => {
    const array = Object.entries(error).map(([clave, valor]) => ({
        clave,
        valor,
    }));
    if (!array || array.length === 0) {
        return "No errors";
    }
    return array
        .map(
            (e) =>
                `- <strong>Input:</strong> ${e.clave} mensaje: ${e.valor.map(
                    (e) => e
                )}`
        )
        .join("</br>");
};
calcularmontos = () => {
    let cantidad = Number($("#cantidad").val());
    let monto = Number($("#monto").val());
    let resultado = 0;
    if (cantidad > 0) {
        resultado = cantidad * monto;
    }
    $("#txttotalfinal").text(`$${resultado.toFixed(2)}`);
};
