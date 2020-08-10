msg_confirmacao = (msgentrada, fn) => {
    swal({
        title: msgentrada[0],
        text: msgentrada[1],
        type: msgentrada[2],
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn-" + msgentrada[4],
        confirmButtonText: msgentrada[3],
        closeOnConfirm: false
    }, fn);
};
