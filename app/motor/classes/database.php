<?php
    /* Refazer sistema de DB */

    class database {
        private $dados = array();
        private $dir = null;

        public function __construct($database=-1,$senha=-1){
            $this->dir = dirname(dirname(__DIR__)) . "/database";
            if($database!==-1){
                $this->iniciar($database,$senha);
            }
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

        public function iniciar($database="global",$senha=-1){
            $this->database = $database;
            $this->senha = $senha !== -1 ? md5($senha) : -1;
            $this->atualizar();
        }

        public function atualizar(){
            if(file_exists(($dbdir = "$this->dir/{$this->database}.db"))){
                $dados = file_get_contents($dbdir);
                if($this->senha!==-1){
                    $dados = $this->decodificar($dados,$this->senha);
                }
                $this->dados = unserialize($dados);
            } else {
                $this->dados = array();
            }
        }

        public function escrever($chave, $valor){
            $this->dados[$chave] = $valor;
        }

        public function ler($chave="*",$obj=false,$reload=false){
            if($reload){
                $this->atualizar();
            }
            return $obj ? ($chave=="*"?(object)$this->dados:(is_array($this->dados[$chave])?(object)$this->dados[$chave]:$this->dados[$chave])):($chave=="*"?$this->dados:$this->dados[$chave]);
        }

        public function gravar(){
            $dados = serialize($this->dados);
            echo "$this->dir/{$this->database}.db";
            $saida = file_put_contents("$this->dir/{$this->database}.db",$this->senha==-1?$dados:$this->codificar($dados,$this->senha));
            $this->atualizar();
            return $saida;
        }
    }
