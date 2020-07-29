<?php
    class motor {

        /* Inicialização */

        function __construct(){
            $this->classLoader();
            $this->appLoad();
            $this->render();
        }

        /* Funções internas */

        private function classLoader(){
            function my_autoload ($pClassName) {
                include(__DIR__ . "/classes/" . $pClassName . ".php");
            }
            spl_autoload_register("my_autoload");
        }

        private function appLoad(){
            $this->app = json_decode(file_get_contents(__DIR__ . "/app.json"));
            if($this->app->https && $_SERVER["HTTPS"] != "on"){
                header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                exit();
            }
            $this->app->appDir = dirname(__DIR__);
            $this->app->publicDir = dirname($this->app->appDir) . "/public_html";
            $this->app->page = $_SERVER['REQUEST_URI'];
            $this->app->vars = array();
        }

        private function minifyCode($input){
            return preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'',$input));
        }

        private function loadPage($index){
            $pagina = $this->app->paginas->{"$index"};
            $exec = false;

            if($pagina->controle){
                include_once "{$this->app->appDir}/controles/{$pagina->controle}.php";
                $exec = "ctrl_{$pagina->controle}";
            }

            $this->regVar("layout", $pagina->layout
                ? file_get_contents("{$this->app->appDir}/layouts/{$pagina->layout}.html")
                : ""
            );

            $modelo = (
                $pagina->modelo
                    ? file_get_contents("{$this->app->appDir}/modelos/{$pagina->modelo}.html")
                    : "%layout%"
            );

            foreach($pagina->variaveis as $chave => $variavel){
                $this->regVar($chave, $variavel);
            }

            foreach($pagina->incluir as $chave => $variavel){
                if(file_exists($html = "{$this->app->appDir}/modelos/{$variavel}.html")){
                    $this->regVar($chave, file_get_contents($html));
                } elseif(file_exists($json = "{$this->app->appDir}/modelos/{$variavel}.json")){
                    $json = file_get_contents($json);
                    $json = json_decode($json,true);
                    $finalcontent = "";
                    foreach($json["montagem"] as $busca){
                        $elemento = isset($json["contem"][$busca])?$json["contem"][$busca]:-1;

                        if($elemento!==-1){
                            $e_layout = file_get_contents("{$this->app->appDir}/layouts/{$elemento["layout"]}.html");

                            foreach($elemento["vars"] as $var=>$value){
                                $e_layout = implode($value,explode("%{$var}%",$e_layout));
                            }

                            $finalcontent .= ($e_layout);
                        }
                    }

                    $this->regVar($chave, $finalcontent);
                }
            }

            if(empty($this->app->vars["layout"]) && $modelo = "%layout%"){
                $this->app->modelo = "";
                unset($this->app->vars["layout"]);
            } else {
                $scripts = "";

                foreach($pagina->scripts as $script){
                    $scripts .= file_get_contents("{$this->app->appDir}/scripts/{$script}.js") . "\n";
                }

                $this->regVar("scriptcode", $scripts);

                $styles = "";

                foreach($pagina->styles as $style){
                    $styles .= file_get_contents("{$this->app->appDir}/styles/{$style}.css") . "\n";
                }

                $this->regVar("stylecode", $styles);

                $this->app->modelo = $modelo;
            }

            $this->app->contentType = $pagina->type;

            if($exec){
                $exec($this);
            }
        }

        private function makePage(){
            header("Content-Type: {$this->app->contentType}");
            $output = $this->app->modelo;
            foreach($this->app->vars as $var=>$value){
                $output = implode($value,explode("%{$var}%",$output));
            }
            echo $this->app->minify ? $this->minifyCode($output):$output;
        }

        private function render(){
            $ready = false;
            $caminhos = array();
            foreach($this->app->paginas as $index => $pagina){
                foreach($pagina->caminho as $caminho){
                    $caminhos[$caminho] = $index;
                    if(($caminho == $this->app->page || "{$caminho}/" == $this->app->page) && !$ready){
                        $ready = true;
                        $this->loadPage($index);
                    }
                }
            }
            if(!$ready) {
                $match = 0;
                foreach($caminhos as $url => $id){
                    $url  = explode("/", $url);
                    $page = explode("/", $this->app->page);
                    if(($exp=$this->compare($url,$page,false)) > $match && $this->app->paginas->{$id}->persistente){
                        $ready = true;
                        $match = $exp;
                        $index = $id;
                    }
                }
                if($ready){
                    $this->loadPage($index);
                }
            }

            if(!$ready) {
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
            } else {
                $this->makePage();
            }
        }

        /* Funções publicas */

        public function regVar($var, $val){
            $this->app->vars[(string)$var] = (string)$val;
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
