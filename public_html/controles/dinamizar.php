<?php

    class dinamizar {
        private $partes = array();

        public function inserir($chave,$ctx=false){
            return isset($this->partes[$chave])
                ?(
                    file_exists($this->partes[$chave])
                        ? $this->chamar($this->partes[$chave], $ctx)
                        : $this->partes[$chave]
                )
                :"";
        } 

        private function chamar($arquivo,$ctx){
            require $arquivo;
            $fn = explode(".", $arquivo);
            $fn = explode("/", $fn[1]);
            $fn = $fn[2];
            if(function_exists($fn)){
                $ctx?$fn($ctx):$fn();
            }
        }

        public function definir($chave,$valor=false){ 
            $valor = $valor?$valor:$chave;
            return ($this->partes[$chave]=file_exists("./modelos/{$valor}.dinamizar.php")?"./modelos/{$valor}.dinamizar.php":$valor);
        }
    }

?>