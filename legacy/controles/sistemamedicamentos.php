<?php

    class sistemamedicamentos {
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
                    $resultado[] = $this->db->dados["data"][$data];
                }

                return $resultado;
            } elseif($key=="*") {
                return $this->db->dados;
            } else {
                return $this->db->dados["data"][$key];
            }
        }

        private function select($estabelecimentoId){
            $this->db->abrir(md5("medicamentos::{$estabelecimentoId}"));
        }

        public function estabelecimentoId($id){
            $this->select((string)$id);
        }

        public function adicionar($dados){
            $this->db->dados["lastid"] = isset($this->db->dados["lastid"]) ? (int)$this->db->dados["lastid"] + 1 : 0;
			$this->db->dados["data"] = !isset($this->db->dados["data"])?array():$this->db->dados["data"];
            $this->db->dados["data"][$this->db->dados["lastid"]] = $dados;

            $this->db->gravar();
        }

        public function editar($id, $dados, $reset = false){
            if($reset){
                $this->db->dados["data"][(int)$id] = array();
            }
            foreach($dados as $chave=>$dado){
                $this->db->dados["data"][(int)$id][$chave] = $dado;
            }

            $this->db->gravar();
        }
    }

?>
