<?php

    class sistemapontos {
        private $db = null;

        function __construct(){
            $this->db = new database("gestaopontos");
			$this->db->proteger();
        }

        private function write($array){
            foreach($array as $key=>$value){
                $this->db->dados[$key] = $value;
            }
            $this->db->gravar();
            return $this->db->dados;
        }

        public function ler($key="*"){
            if(is_array($key)){
                $resultado = array();

                foreach($key as $data){
                    $resultado[] = $this->db->dados[$data];
                }

                return $resultado;
            } elseif($key=="*") {
                return $this->db->dados;
            } else {
                return $this->db->dados[$key];
            }
        }

        private function select($estabelecimentoId){
            $this->db->abrir(md5("estabelecimento::{$estabelecimentoId}"));
        }

        public function estabelecimentoId($id){
            $this->select((string)$id);
            if(!isset($this->db->dados["pontos"])){
                $this->write(array(
                    "pontos" => 250,
                    "transacoes" => array()
                ));
            }
        }

        public function registrarTransacao($transacao){
            $this->db->dados["transacoes"][] = $transacao;
            $this->db->gravar();
        }

        public function registrarPontos($pontos){
            $this->db->dados["pontos"] = (int)$this->db->dados["pontos"] + (int)$pontos;
            $this->db->gravar();
        }
    }

?>
