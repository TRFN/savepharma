<?php
    function ctrl_nfs($ctx){
        if(!$ctx->uploader->existe($arq="nfs/{$ctx->urlParams[2]}/{$ctx->urlParams[3]}")){
            header("Content-Type: text/plain");
            die("Desculpe, mas o arquivo solicitado não pôde ser encontrado.");
        }
        $ext = $ctx->uploader->dados($arq)->ext;
        if(isset($ctx->urlParams[4]) && $ctx->urlParams[4] == "download"){
            $dwl = "NF-" . date("dmYHis");
            header("Content-Disposition: attachment; filename=\"{$dwl}.{$ext}\"");
        }
        if(isset($ctx->urlParams[4]) && $ctx->urlParams[4] == "json"){
            header("Content-Type: application/json");
            exit(json_encode($ctx->uploader->dados($arq, false)));
        } else $ctx->uploader->display($arq);
    }
