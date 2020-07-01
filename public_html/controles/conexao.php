<?php
    class conexao {
        private $db = null;
        private $id = -1;
        private $other = false;

        public function __construct($other=false){
            $this->db = new database("contas-admin");
			$this->db->vetor();
            $this->db->proteger();
            if(!$other){
                # Se já está conectado, mantém a conexão.
                if(isset($_SESSION["email"]) && isset($_SESSION["senha"])){
                    if(!$this->conectar($_SESSION["email"],$_SESSION["senha"],false)){
                        unset($_SESSION["senha"]);
                        unset($_SESSION["email"]);
                    }
                }
            } else {
                $this->other = true;
            }
        }

        public function conectado(){
            return $this->id !== -1;
        }

        public function conectar($email, $senha, $registrarUltimoAcesso = true){
            $this->db->abrir("contas");
            if(count($this->db->dados)){
                foreach($this->db->dados as $conta){
                    if(!empty($conta["email"]) && !empty($conta["senha"]) && strtolower($conta["email"]) == strtolower($email) && $conta["senha"] == $senha){
                        $_SESSION["email"] = $conta["email"];
                        $_SESSION["senha"] = $conta["senha"];
                        $this->id = $conta["id"];
                        $this->db->abrir($conta["id"]);
                        /*print_r($this->db->dados);
                        exit;*/
                        if($registrarUltimoAcesso){
                            $this->db->dados["data-ultimoacesso"] = isset($this->db->dados["data-reg"])?$this->db->dados["data-reg"]:date("d/m/Y \a\s H:i:s");
                            $this->db->dados["data-reg"] = date("d/m/Y \a\s H:i:s");
                            $this->db->gravar();
                        }
                        return count($this->db->dados)>0;
                    }
                    /*print_r($this->db->dados);
                    exit;*/
                }
            }
            return false;
        }

        public function listar(){
            $this->db->abrir("contas");
            $contastotais = $this->db->dados;
            $contas = array();
            if(count($contastotais)){
                foreach($contastotais as $id=>$conta){
                    if(!empty($conta["email"]) && !empty($conta["senha"])){
                        $this->db->abrir($conta["id"]);
                        $contas[$id] = $this->db->dados;
                        $contas[count($contas)-1]["sessaoatual"] = $conta["id"]==$this->id?"sim":"nao";
                        if(!isset($contas[count($contas)-1]["ativo"])){
                            $contas[count($contas)-1]["ativo"] = "sim";
                        }
                    }else{
						$contas[$id] = array("__apagado__"=>true);
					}

                }
				$contas = array_values($contas);
            }
            return $contas;
        }

        public function ativo($email=0){
            if($email==0)return "-1";

            $contas = $this->listar();

            return $contas[$email]["ativo"] == "sim";
        }

        public function existe($dado,$valor){
            $this->db->abrir("contas");
            if(count($this->db->dados)){
                foreach($this->db->dados as $conta){
                        $this->db->abrir($conta["id"]);
                        if(isset($this->db->dados[$dado])&&$valor==$this->db->dados[$dado])return true;
                    }
                }
            return false;
        }

        public function criarconta($dados,$autoconn=false){
            $this->db->abrir("contas");

            foreach($this->db->dados as $conta){
               if(strtolower($conta["email"]) == strtolower($dados["email"])){
                   return "conta-existe";
               }
            }

            $id = count($this->db->dados);

            $dados["id"] = $id;
            $dados["data-registro"] = date("d/m/Y");
            $dados["data-ultimoacesso"] = date("d/m/Y \a\s H:i:s");

            $this->db->dados[(int)$id] = array(
                "id" => $id,
                "email" => $dados["email"],
                "senha" => $dados["senha"]
            );

            $this->db->gravar();

            $this->db->abrir($dados["id"]);

            if($autoconn){
                $this->conectar($dados["email"],$dados["senha"]);
                $this->id = $dados["id"];
            }

            unset($dados["senha"]);

            $this->db->dados = $dados;

            $this->db->gravar();

            return "ok";
        }

        public function usuario(){
            if(!$this->conectado())return false;
            $this->db->abrir($this->id);
            $dados = $this->db->dados;
            unset($dados["data-reg"]);
            return $dados;
        }

        public function uid(){
            return $this->id;
        }

        public function mudardado($dado,$valor,$id=-1){
            if($id==-1){
                return false;
            }

            if($id=="self"){
                $id = $this->id;
            }

            // return $dado . "\n";

            if($dado == "email" || $dado == "senha"){
                $this->db->abrir("contas");
                $this->db->dados[$id][$dado] = $valor;
                if(!$this->other){
                    $_SESSION[$dado] = $valor;
                }
                if($id>-1): $this->db->gravar(); endif;
            }

            if($dado != "senha"){
                $this->db->abrir($id);
                $this->db->dados[$dado] = $valor;
                $this->db->gravar();
            }
            // $this->db->abrir("contas");
            // return print_r($this->db->dados,true) . "\n";

            return true;
        }

        public function remover($id=-1){
            if($id==-1)return false;

            if($id=="self"||$id==$this->uid()){
                return false;
            }

            $this->db->abrir("contas");
            $this->db->dados[$id]["email"] = "";
            $this->db->dados[$id]["senha"] = "";
            $this->db->gravar();
            $this->db->apagar($id);

            return true;
        }

        public function gerarsenha($tamanho = 6, $maiusculas = false, $numeros = true, $simbolos = false){
            $lmin = 'abcdefghijklmnopqrstuvwxyz';
            $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $num = '1234567890';
            $simb = '!@#$%*-';
            $retorno = '';
            $caracteres = '';
            $caracteres .= $lmin;
            if ($maiusculas) $caracteres .= $lmai;
            if ($numeros) $caracteres .= $num;
            if ($simbolos) $caracteres .= $simb;
            $len = strlen($caracteres);
            for ($n = 1; $n <= $tamanho; $n++) {
                $rand = mt_rand(1, $len);
                $retorno .= $caracteres[$rand-1];
            }
            return $retorno;
        }

        public function desconectar(){
            unset($_SESSION["senha"]);
            unset($_SESSION["email"]);

            $this->id = -1;
        }

        public function gerartoken($email=-1){
            if($email==-1)return false;

            $contas = $this->listar();

            if(!isset($contas[urldecode($email)])){
                return false;
            }

            $token = md5(uniqid().uniqid().uniqid().mt_rand(5,50));

            $this->db->abrir("tokens");
            $this->db->dados[$token] = $contas[$email]["id"];
            $this->db->gravar();

            return $token;

        }

        public function utilizartoken($token){
            $this->db->abrir("tokens");

            if(isset($this->db->dados[$token])){
                $u = $this->db->dados[$token];
                unset($this->db->dados[$token]);
                $this->db->gravar();
                $novasenha = $this->gerarsenha();
                $this->db->abrir("contas");
                $this->db->dados[(int)$u]["senha"] = md5($novasenha);
                $this->db->gravar();

                return $novasenha;
            }

            return false;

        }
    }
