<?php
    class motor {

        /* Inicialização */

        function __construct(){
            ini_set('memory_limit', '-1');
            date_default_timezone_set('America/Sao_Paulo');

            $this->classLoader();
            $this->rescheck();
            $this->appLoad();
            $this->render();
        }

        private function rescheck(){
            $www = realpath(dirname(dirname(__DIR__)) . "/public_html" . $_SERVER["REQUEST_URI"]);

            $ext = pathinfo($www,PATHINFO_EXTENSION);

            $code = "";

            if(file_exists($www) && !is_dir($www)){
                switch($ext){
                    case "css":
                        $code = file_get_contents($www);
                        $code = (preg_replace(['/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','/\n/'],['>','<','\\1',''],$code));
                        $mime = "text/css";
                    break;

                    case "js":
                        $jsmin = new jsmin();
                        $code = $jsmin->minify(file_get_contents($www));
                        $mime = "application/x-javascript";
                    break;

                    case "json":
                        $code = file_get_contents($www);
                        $mime = "application/json";
                    break;
                }

                header("Content-Type: {$mime}");
                exit($code);
            }
        }

        /* Funções internas */

        private function classLoader(){
            function my_autoload ($pClassName) {
                include(__DIR__ . "/classes/" . $pClassName . ".php");
            }
            spl_autoload_register("my_autoload");
        }

        private function https(){
            $https = [
                (isset($this->app->https) && $this->app->https && $_SERVER['SERVER_ADDR'] != "127.0.0.1"),
                ((! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
                (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
                (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443'))
            ];

            if($https[0] && !$https[1]){
                header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                exit;
                // die("https: {$_SERVER['SERVER_ADDR']} {$this->app->https}\n".print_r($this->app, true));
            } elseif(!$https[0] && $https[1]){
                header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                exit;
                // die("http: {$_SERVER['SERVER_ADDR']} {$this->app->https}\n".print_r($this->app, true));
            } else {
                return $https[0] == $https[1];
            }
        }

        private function appLoad($app="app"){
            $this->jsmin = new jsmin();
            $this->app = json_decode(file_get_contents(__DIR__ . "/{$app}.json"));
            $this->app->appDir = dirname(__DIR__);
            $this->app->publicDir = dirname($this->app->appDir) . "/public_html";
            $this->app->page = $_SERVER['REQUEST_URI'];
            $this->applyDefaultVars();
            $this->urlParams = explode("/", $this->app->page);
            array_shift($this->urlParams);
        }

        private function minifyCode($input){
            return preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'',$input));
        }

        private function loadPage($index){
            $pagina = $this->app->paginas->{"$index"};

            $this->app->minify = $pagina->minify && $this->app->minify;

            $exec = array();

            if(is_string($pagina->controle)){
                include_once "{$this->app->appDir}/controles/{$pagina->controle}.php";
                $exec[] = "ctrl_" . preg_replace("/\//","_",$pagina->controle);
            } elseif(is_array($pagina->controle)){
                foreach($pagina->controle as $controle){
                    include_once "{$this->app->appDir}/controles/{$controle}.php";
                    $exec[] = "ctrl_" . preg_replace("/\//","_",$controle);
                }
            }

            $this->regVarStrict("layout", $pagina->layout
                ? $pagina->layout
                : ""
            );

            $modelo = $this->str2res(
                $pagina->modelo
                    ? $pagina->modelo
                    : "%layout%"
            );

            foreach($pagina->variaveis as $chave => $variavel){
                $this->regVarSuper($chave, $variavel);
            }

            if(empty($this->app->vars["layout"]["val"]) && $modelo = "%layout%"){
                $this->app->modelo = "";
                unset($this->app->vars["layout"]);
            } else {
                $scripts = "";

                foreach($pagina->scripts as $script){
                    $scripts .= file_get_contents("{$this->app->appDir}/scripts/{$script}.js") . "\n";
                }

                $scripts = $this->jsmin->minify($scripts);

                $this->regVarStrict("scriptcode", $scripts);

                $styles = "";

                foreach($pagina->styles as $style){
                    $styles .= file_get_contents("{$this->app->appDir}/styles/{$style}.css") . "\n";
                }

                $this->regVarStrict("stylecode", preg_replace(['/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','/\n/'],['>','<','\\1',''],$styles));

                $this->app->modelo = $modelo;
            }

            $this->app->contentType = $pagina->type;

            if(count($exec) > 0){
                foreach($exec as $_exec){
                    $_exec($this);
                }
            }
        }

        private function str2res($str){
            if(file_exists($html = "{$this->app->appDir}/modelos/{$str}.html")){
                return file_get_contents($html);
            } elseif(file_exists($html = "{$this->app->appDir}/layouts/{$str}.html")){
                return file_get_contents($html);
            } elseif(file_exists($json = "{$this->app->appDir}/modelos/{$str}.json")){
                $json = file_get_contents($json);
                $json = json_decode($json,true);
                $finalcontent = "";
                if(is_string($json["contem"])){
                    $json["contem"] = file_get_contents("{$this->app->appDir}/modelos/{$json["contem"]}.json");
                    $json["contem"] = json_decode($json["contem"],true);
                    $json["contem"] = $json["contem"]["contem"];
                }
                foreach($json["montagem"] as $busca){
                    $elemento = isset($json["contem"][$busca])?$json["contem"][$busca]:-1;

                    if($elemento!==-1){
                        $e_layout = file_get_contents("{$this->app->appDir}/layouts/{$elemento["layout"]}.html");

                        foreach($elemento["vars"] as $var=>$value){
                            if(is_object($value) || is_array($value)){
                                $value = json_encode($value);
                            }
                            $e_layout = implode($value,explode("%{$var}%",$e_layout));
                        }

                        $finalcontent .= ($e_layout);
                    }
                }

                return $finalcontent;
            } else {
                return $str;
            }
        }

        private function makePage(){
            header("Content-Type: {$this->app->contentType}");
            $output = $this->app->modelo;
            for($i = 0; $i < 3; $i++ ){
                foreach($this->app->vars as $param){
                    $output = implode($param["val"], explode("%{$param["var"]}%",$output));
                }

                foreach($this->app->globVars as $var => $val){
                    $output = implode($val, explode("%{$var}%",$output));
                }
            }

            echo $this->app->minify ? $this->minifyCode($output):$output;
        }

        private function render(){
            $ready = 0;
            $caminhos = array();
            foreach($this->app->paginas as $index => $pagina){
                foreach($pagina->caminho as $caminho){
                    $caminhos[$caminho] = $index;
                    if(($caminho == $this->app->page || "{$caminho}/" == $this->app->page) && !$ready){
                        $ready = 1;
                        $this->loadPage($index);
                    }
                }
            }
            if($ready==0) {
                $match = 0;
                foreach($caminhos as $url => $id){
                    $url  = explode("/", $url);
                    $page = explode("/", $this->app->page);
                    if(($exp=$this->compare($url,$page,false)) > $match && $this->app->paginas->{$id}->persistente){
                        $ready = 1;
                        $match = $exp;
                        $index = $id;
                    }
                }
                if($ready==1){
                    $this->loadPage($index);
                }
            }

            if($ready==0){
                if(file_exists($this->app->appDir . "/motor/" . ($napp = explode("/",$this->app->page)[1]) . ".json")){
                    $this->appLoad($napp);
                    $this->render();
                    $ready = 2;
                }
            }

            if($ready==0) {
                header("HTTP/1.0 404 Not Found");
                if(!$this->app->{"404"}){
                    header("Content-Type: text/plain");
                    echo "Desculpe, mas a página solicitada não pôde ser encontrada. Tente novamente mais tarde!\n";
                    die();
                } else {
                    echo "<!-- Não encontrado 404 -->\n\n";
                    $this->loadPage($this->app->{"404"});
                    $this->makePage();
                }
            } elseif($ready != 2 && $this->https()) {
                $this->makePage();
            }
        }

        private function applyDefaultVars(){
            $this->app->globVars = isset($this->app->vars)?(array)$this->app->vars:array();
            foreach($this->app->globVars as $chave=>$valor){
                $this->app->globVars[$chave] = $this->str2res($valor);
            }
            $this->app->vars = array();
        }

        /* Funções publicas */

        public function regVar($var, $val){
            $this->app->vars[] =  array("var" => (string)$var, "val" => $this->str2res((string)$val));
        }

        public function regVarStrict($var, $val){
            $this->app->vars[$var] =  array("var" => (string)$var, "val" => $this->str2res((string)$val));
        }

        public function regVarPersistent($var, $val){
            $this->app->globVars[(string)$var] = $this->str2res((string)$val);
        }

        public function regVarSuper($var, $val){
            $vars_backup = $this->app->vars;

            $this->app->vars = array();

            $this->regVar($var, $val);
            $this->regVarPersistent($var, $val);

            $protected = ["layout","scriptcode", "stylecode"];

            foreach($protected as $processo){
                if(isset($vars_backup[$processo])):
                    $this->regVarStrict($processo, $vars_backup[$processo]["val"]);
                    unset($vars_backup[$processo]);
                endif;
            }

            $this->regVar($var, $val);

            foreach($vars_backup as $_var){
                $this->regVar($_var["var"], $_var["val"]);
                $this->regVar($var, $val);
            }
        }

        public function compare($arrA, $arrB, $exact=true){
            $match = 0;
            foreach($arrA as $key=>$val){
                if($exact && $arrB[$key] != $val){
                    return false;
                }
                if(!$exact && $arrB[$key] == $val){
                    $match++;
                }
            }
            return $match==0 && $exact ? true : $match;
        }
    }
