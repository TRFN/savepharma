document.addEventListener("DOMContentLoaded", () => {
    $("#nivelacesso").change(e = (() => {
        if($("#nivelacesso")[0].value=='admin'){
            $('#vinculo').prop('disabled',true).trigger('chosen:updated');
            $('.dinamico-tipo').text('Opção desabilitada para este tipo de conta').css({color:'#888'});
        } else {
            $('.dinamico-tipo').text($("#nivelacesso").find('option[value=' + $("#nivelacesso")[0].value + ']').text() + ' de qual estabelecimento?').css({color:'#333'});$('#vinculo').prop('disabled', false).trigger('chosen:updated');
        }
    }));
    e();
}, !1);
