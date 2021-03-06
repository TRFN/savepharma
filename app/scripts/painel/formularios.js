$("select").chosen({"no_results_text": 'Nenhum resultado encontrado para: <br /><br /><strong>'});

$(".chosen-container .chosen-single").addClass("form-control").css({
    "border-radius": 0,
    "display": "block",
    "width": "100%",
    "height": "34px",
    "padding": "6px 12px",
    "font-size": "14px",
    "line-height": "1.42857143",
    "color": "#555",
    "background-color": "#fff",
    "background-image": "none",
    "border": "1px solid #ccc",
    "-webkit-box-shadow": "inset 0 1px 1px rgba(0,0,0,.075)",
    "box-shadow": "inset 0 1px 1px rgba(0,0,0,.075)",
    "-webkit-transition": "border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s",
    "-o-transition": "border-color ease-in-out .15s,box-shadow ease-in-out .15s",
    "transition": "border-color ease-in-out .15s,box-shadow ease-in-out .15s",
});

$('.chosen-container b').css({
    "margin-top": "4.42857143px"
}).closest(".chosen-container").css({width: "100%"});

$("._ca:not(.%tipo-acesso%)").remove();

$(".form-group %input-error%").closest(".form-group").addClass("has-error");

$(".form-group .form-control.data").datepicker({"language":"pt-BR"}).blur(function(){
    if(!/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/.test(this.value)){
        this.value = "";
        $(this).closest(".form-group").addClass("has-error");
    }
});

$(".form-group .form-control").on("keydown keyup change focus",function(){
    if($(this).closest(".form-group").hasClass("has-error")){
        $(this).closest(".form-group").removeClass("has-error");
    }
});
