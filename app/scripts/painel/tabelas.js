document.addEventListener("DOMContentLoaded", () => {
    $('#%tabela%').DataTable( {
        language: {
            "sEmptyTable":   "Não foi encontrado nenhum registro",
            "sLoadingRecords": "A carregar...",
            "sProcessing":   "A processar...",
            "sLengthMenu":   "",
            "sZeroRecords":  "Não foram encontrados resultados",
            "sInfo":         "Mostrando de _START_ até _END_ de _TOTAL_ registros.",
            "sInfoEmpty":    "Não há registros listados",
            "sInfoFiltered": "(filtrado de _MAX_ registros no total)",
            "sInfoPostFix":  "",
            "sSearch":       "Pesquisar: ",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Primeiro",
                "sPrevious": "<button class='btn btn-default btn-outline'><i class='fa fa-arrow-left'></i></button>",
                "sNext":     "<button class='btn btn-default btn-outline'><i class='fa fa-arrow-right'></i></button>",
                "sLast":     "Último"
            },
            "oAria": {
                "sSortAscending":  ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        data: (dados=%dados%),
        columns: %titulos%,
        lengthMenu: [[%qtdRes%], ["Todos"]]
    } );

    bl_start = "<div style='margin: 2px;padding: 2px;'>";
    st_start = "<div style='padding: 8px; font-size: 16px; letter-spacing: -0.2px; font-weight: 600;' class='resultado col-sm-6 col-xs-12 text-center'>";
    end = "</div>";

    for( i in dados ){
        $('#%tabela%-mobile').append(st_start+bl_start+(dados)[i].join(end+bl_start)+end+end);
    }

    $("#%tabela%-pesquisa").keyup(function(){
        achou = false;
        $("#%tabela%-mobile .resultado").each(function(){
            pesq = (new RegExp($("#%tabela%-pesquisa").val().toLowerCase(), 'i')).test(this.innerText);

            if(!pesq){
                $(this).hide();
            } else {
                $(this).show();
                achou = true;
            }
        });

        $("#%tabela%-mobile #mensagem").text(!achou?"Nenhum resultado encontrado.":"");
    });

    $(".dataTables_wrapper input").addClass("form-control").css({"width": "21vw","display": "inline-block","margin-bottom": "16px"});
}, !1);
