<?php
    class uploader {
        private $db = null;
        private $param = null;
        private $exts = array();
        private $id = null;
        private $dir = "";
        private $valido = false;
        private $iext = "";

        function __construct($security=-1){
            $this->db = new database("uploader-control", $security);
            $this->dir = dirname(dirname(__DIR__)) . "/uploads";
        }

        public function ler($param){
            $this->param = $param;
        }

        public function ext($exts){
            if(is_string($exts)): $exts = array($exts); endif;
            $this->exts = $exts;
        }

        public function id($id){
            $this->id = $id;
        }

        public function valido(){
            $iext = strtolower(pathinfo(basename($_FILES[$this->param]["name"]),PATHINFO_EXTENSION));
            $correct = false;

            foreach($this->exts as $ext){
                $correct = $correct || ((string)$iext === (string)$ext);
            }

            if ($_FILES[$this->param]["size"] > 100000000 || !$correct){
                return false;
            }

            $id = explode("/", $this->id);
            $myid = array_pop($id);
            $dir = implode("/",$id);

            mkdir("{$this->dir}/{$dir}");

            $this->iext = $iext;
            // die(print_r($_FILES,true));
            // die(print_r($this,true));
            return ($this->valido=move_uploaded_file($_FILES[$this->param]["tmp_name"], ($this->arquivosalvo="{$this->dir}/{$dir}/{$myid}.{$iext}")));
        }

        public function upload(){
            if($this->valido){
                $this->db->escrever($this->id, array(
                    "mime" => $_FILES[$this->param]["type"],
                    "ext" => $this->iext,
                    "arquivo" => $this->arquivosalvo,
                    "data" => array(date("d"),date("m"),date("Y"))
                ));
                $this->db->gravar();
            } else {
                // die(print_r($_FILES,true));
            }

            $this->valido = false;
            $this->iext = "";
            $this->arquivosalvo = "";
            $this->param = null;
            $this->exts = array();
            $this->id = null;
        }

        public function existe($id){
            return !($this->db->ler($id) === null);
        }

        public function dados($id,$obj = true){
            return $this->db->ler($id, $obj);
        }

        public function apagar($id){
            if($this->existe($id)){
                unlink($this->dados($id)->arquivo);
                $this->db->escrever($id, null);
                $this->db->gravar();
            }
        }

        public function display($id){
            if($this->existe($id)){
                $arq = $this->db->ler($id);
                header("Content-Type: {$arq["tipo"]}");
                readfile($arq["arquivo"]);
                exit;
            } else {
                header("Content-Type: text/plain");
                exit("Este arquivo não pôde ser encontrado!");
            }
        }
    }
?>
