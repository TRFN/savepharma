<?php
    function ctrl_nfs($ctx){
        $ctx->uploader->display("nfs/{$ctx->urlParams[2]}/{$ctx->urlParams[3]}");
    }
