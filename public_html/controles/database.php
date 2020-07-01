<?php
    class database {
        private $secao = false;
        public  $dados = array();
        private $nome  = false;
        private $senha = false;
        private $proteger = false;
		private $vetor = false;
        
        function __construct($secao=false,$senha=false,$protegido=false){
            if($secao){
                $this->secao = "#" . md5($secao);
            } else {
                $this->secao = "#" . md5("global");
            }
            if($senha){
                $this->senha = sha1($senha);
            } else {
                $this->senha = sha1($secao);
            }
            $this->proteger = $protegido;
            
            if(!is_dir($this->secao)){
                mkdir($this->secao);
                file_put_contents($this->secao . "/index.php","<?php header('Location: ../'); ?>");
            }
        }
		
		public function vetor($def=true){
			$this->vetor = $def;
		}
        
        private function baseDeCodificacao($texto, $chave, $fator){
			$chave = sha1($chave);
			
			$chaveDividida = str_split($chave);
			$tamChaveDividida = count($chaveDividida);
			$textoDividido = str_split($texto);
			$tamTextoDividido = count($textoDividido);
			
			foreach( $textoDividido as $ordem => $caracter ){
				$codigo = ord($chaveDividida[$ordem%$tamChaveDividida]);
				$numero = ord($caracter);
				$textoDividido[$ordem] = chr($numero+($codigo*$fator*((bool)$ordem%2?-1:1)));
			}
			
			return implode("", $textoDividido);
		}
		
		private function codificar($texto, $chave){
			return $this->baseDeCodificacao( $texto, $chave, -1 );
		}
		
		private function decodificar($texto, $chave){
			return $this->baseDeCodificacao( $texto, $chave,  1 );
		}
        
        public function abrir($nome,$protegido=false){
            if($protegido){$this->proteger();}
            $nome = "#" . md5($nome);
            $dado = array();
            $indice = 0;
            $arquivo = $this->secao . "/{$nome}";
            do {
                $arquivofinal = $arquivo . md5($indice);
                if(file_exists($arquivofinal)){
                    $data = file_get_contents($arquivofinal);
                    foreach(($this->proteger?unserialize($this->decodificar($data,$this->senha)):unserialize($data)) as $chave=>$datafinal){
                        $dado[$chave] = $datafinal;
                    }
                } else {
                    break;
                }
                $indice++;
            } while(file_exists($arquivofinal));
            $this->nome = $nome;
            $this->dados = $this->vetor ? ($dado):$dado;
        }
        
        public function apagar($nome){
            $nome = "#" . md5($nome);
            $indice = 0;
            $arquivo = $this->secao . "/{$nome}";
            do {
                $arquivofinal = $arquivo . md5($indice);
                if(file_exists($arquivofinal)){
                    unlink($arquivofinal);
                } else {
                    break;
                }
                $indice++;
            } while(file_exists($arquivofinal));
        }
        
        public function carregar($nome, $chave = -1){
            $nome = "#" . md5($nome);
            $dado = array();
            $indice = $chave==-1?0:floor($chave*0.001);
            $arquivo = $this->secao . "/{$nome}";
            $arquivofinal = $arquivo . md5($indice);
            if(file_exists($arquivofinal)){
                $data = file_get_contents($arquivofinal);
                foreach(($this->proteger?unserialize($this->decodificar($data,$this->senha)):unserialize($data)) as $ch=>$datafinal){
                    $dado[$ch] = $datafinal;
                }
            }
    
            if($chave < 0): return count($dado)?$dado:false;
            elseif(isset($dado[$chave])): return $dado[$chave];
            else: return false; endif;
        }
        
        public function gravar(){
            $dados = array_chunk($this->dados, 1000, true);
            foreach($dados as $pos=>$dado){
                $pos = md5($pos);
                $data = $this->proteger?$this->codificar(serialize($dado),$this->senha):serialize($dado);
                $arquivo = $this->secao . "/{$this->nome}{$pos}";
                $arquivo = fopen($arquivo, "w");
                fwrite($arquivo,$data);
                fclose($arquivo);
            }
        }
        
        public function proteger(){
            $this->proteger = true;
        }
        
        public function desproteger(){
            $this->proteger = false;
        }
    }