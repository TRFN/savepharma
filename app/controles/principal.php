<?php
    function ctrl_principal($ctx){
        $ctx->regVar("anoatual", date("Y"));
        $ctx->regVar("maxcol-8-10-12", "col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0");
        $ctx->regVar("menu-separator", '<li><a href="#" style="border:0!important;cursor:default!important;" onclick="return false">|</a></li>');
    }
?>
