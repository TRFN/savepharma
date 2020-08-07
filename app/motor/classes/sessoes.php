<?php
    class sessoes {
        public  $u_name = "email";
        public  $u_pass = "senha";
        public  $erros  = null;
        private $conexao = null;
        private $db = null;

        function __construct($id,$debug=false){
            session_start();

            $this->conexao = isset($_SESSION["hash"]) && !empty($_SESSION["hash"]) ? $_SESSION["hash"]:null;

            $this->erros = new stdClass;
            $this->erros->login = "";
            $this->erros->criar_conta = "";
            $this->erros->alterar_dado = "";

            $this->db = new database($id,$debug?-1:md5($id));
        }

        public function criar_conta($dados){
            if($this->conta_disponivel($dados[$this->u_name])){
                $dados[$this->u_pass] = md5($dados[$this->u_pass]);
                $dados["hash"] = sha1(json_encode($dados) . uniqid() . mt_rand(1000,10000));
                $this->db->escrever(-1,$dados);
                $this->db->gravar();

                $this->erros->criar_conta = "";

                return true;
            } else {
                $this->erros->criar_conta = "conta_existe";
            }

            return false;
        }

        public function login($dados){
            if($this->conexao == null){
                foreach($this->db->ler("*") as $index=>$conta){
                    if($conta !== "0"){
                        $senha = $conta[$this->u_pass] == md5($dados[$this->u_pass]);
                        $login = $conta[$this->u_name] == $dados[$this->u_name];

                        if($login && $senha){
                            $_SESSION["hash"] = $conta["hash"];
                            $this->conexao = $_SESSION["hash"];
                            $this->erros->login = "";
                            return true;
                        }

                        elseif($login){
                            break;
                        }

                        else {
                            $login = false;
                            $senha = false;
                        }
                    }
                }

                $this->erros->login = $login ? "senha_incorreta" : "conta_inexistente";
            } else {
                $this->erros->login = "conexao_existente";
            }
            return false;
        }

        public function logout(){
            session_unset();
            $_SESSION["hash"] = "";
            $this->conexao = null;
        }

        public function conexao(){
            foreach($this->db->ler("*") as $index=>$conta){
                if($conta["hash"] == $this->conexao){
                    $conta["id"] = $index;
                    return (object)$conta;
                }
            }

            return -1;
        }

        public function conectado(){
            return $this->conexao() !== -1;
        }

        public function alterar_dado($dados, $conta = -1){
            if($conta == -1){
                $conta = $this->conexao()->id;
            }

            $o_dados = $this->db->ler($conta);

            foreach($dados as $dado => $valor){

                if($dado == $this->u_pass){
                    $valor = md5($valor);
                }

                if($dado != $this->u_name || ($dado == $this->u_name && $this->conta_disponivel($valor))){
                    $o_dados[$dado] = $valor;
                    if($dado == $this->u_name){
                        $this->erros->alterar_dado = "";
                    }
                } elseif($dado == $this->u_name){
                    $this->erros->alterar_dado = "email_existente";
                }
            }

            $this->db->escrever($conta, $o_dados);
            $this->db->gravar();
        }

        public function listar_contas(){
            $contas = array();
            foreach($this->db->ler("*") as $id=>$dados){
                if($dados !== "0"){
                    unset($dados[$this->u_pass]);
                    unset($dados["hash"]);
                    $dados["id"] = $id;
                    $contas[] = $dados;
                }
            }

            return $contas;
        }

        public function conta_disponivel($u_name){
            foreach($this->listar_contas() as $conta){
                if($conta[$this->u_name] == $u_name){
                    return false;
                }
            }
            return true;
        }

        public function apagar_conta($id){
            if($this->conexao()->id == $id){
                $this->logout();
            }
            $this->db->escrever($id,"0");
            $this->db->gravar();
        }
    }
