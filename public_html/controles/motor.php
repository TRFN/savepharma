<?php

function slug($texto){
    $texto = strtolower($texto);

    $texto = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$texto);

    $texto = preg_replace("/[^a-z0-9-]+/i","-",$texto);

    return $texto;
}

class motor {
    private $scripts = array("@global" => array());
    private $estilos = array("@global" => array());
    private $controles = array("@global" => array());
    private $subpaginas = array();
    private $basedir = ".";
    private $paginas = array();
    public function __construct($https=true){
        # FORÇAR HTTPS # 

        if($https && (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")){
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
            exit;
        }
        
        spl_autoload_register(array(
            $this,
            'classesauto'
        ));
        
        foreach (array(
            "interface",
            "controles",
            "modelos",
            "uploads"
        ) as $proteger) {
            $proteger = $this->basedir() . "/{$proteger}/index.php";
            if (!file_exists($proteger)) {
                file_put_contents($proteger, "<?php header('Location: ../'); ?>");
            }
        }
        ini_set('memory_limit','256M');
        set_time_limit(0);
        session_start();
        date_default_timezone_set('America/Sao_Paulo');
    }
    private function classesauto($classe){
        require_once $classe . '.php';
    }
    public function debug(){
        header('Content-Type: text/plain');
        exit(print_r($this));
    }
    public function partes($vetor){
        foreach ($vetor as $instancia => $conteudo) {
            $this->{$instancia} = $conteudo;
        }
    }
    public function basedir($def = false)
    {
        if (!$def) {
            return $this->basedir;
        } else {
            $this->basedir = $def;
            return;
        }
    }
    public function paglist($exclude=true){
        return array_keys($this->paginas);
    }
    public function adcpag($nome, $url = false)
    {
        if (!$url) {
            $url = $nome;
        }
        $this->paginas[$url] = array(
            "interface" => (file_exists($this->basedir() . "/interface/{$nome}.layout.php") ? "/interface/{$nome}.layout.php" : -1),
            "modelo" => (file_exists($this->basedir() . "/modelos/{$nome}.modelo.php") ? "/modelos/{$nome}.modelo.php" : -1),
            "controle" => (file_exists($this->basedir() . "/controles/{$nome}.controle.php") ? "/controles/{$nome}.controle.php" : -1)
        );
    }
    public function adcsub($raiz, $nome, $url = false, $herdar_raiz = false)
    {
        if (!$url) {
            $url = $nome;
        }
        if ($herdar_raiz && !is_array($herdar_raiz)):
            $herdar_raiz = array(
                $herdar_raiz
            );
        endif;
        $this->subpaginas[$url] = array(
            "raiz" => explode("/", $raiz),
            "herdar" => $herdar_raiz,
            "interface" => (file_exists($this->basedir() . "/interface/{$nome}.layout.php") ? "/interface/{$nome}.layout.php" : -1),
            "modelo" => (file_exists($this->basedir() . "/modelos/{$nome}.modelo.php") ? "/modelos/{$nome}.modelo.php" : -1),
            "controle" => (file_exists($this->basedir() . "/controles/{$nome}.controle.php") ? "/controles/{$nome}.controle.php" : -1)
        );
    }
    public function adcres($res, $src, $cat = array("@global"))
    {
        if (is_string($cat)) {
            $cat = array(
                $cat
            );
        }
        foreach ($cat as $para) {
            switch (strtolower($res)) {
                case "script":
                    if (!isset($this->scripts[$para])) {
                        $this->scripts[$para] = array();
                    }
                    $arquivo = $this->basedir() . "/javascript/{$src}.js";
                    $this->scripts[$para][] = file_exists($arquivo)?$arquivo:$src;
                    break;
                case "estilo":
                    if (!isset($this->estilos[$para])) {
                        $this->estilos[$para] = array();
                    }
                    $arquivo = $this->basedir() .  "/css/{$src}.css";
                    $this->estilos[$para][] = file_exists($arquivo)?$arquivo:$src;
                    break;
                case "controle":
                    if (!isset($this->controles[$para])) {
                        $this->controles[$para] = array();
                    }
                    $this->controles[$para][] = $this->basedir() . "/controles/{$src}.php";
                    break;
            }
        }
    }
    private function res2html($vetor, $tipo)
    {
        $saida = "";
        foreach ($vetor as $res) {
            if(!preg_match("/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/", $res)): for($i = 0; $i < count($this->subpaginas()); $i++):
                $res = "./.{$res}";
            endfor; endif;
            switch ($tipo) {
                case "js":
                    $saida .= "<script src='{$res}'></script>";
                    break;
                case "css":
                    $saida .= "<link href='{$res}' rel=stylesheet type='text/css' />";
                    break;
            }
        }
        return $saida;
    }
    public function pagina_atual()
    {
        if (!isset($_REQUEST["_urldir_"]))
            return "";
        $paginas = explode("/", $_REQUEST["_urldir_"]);
        return array_shift($paginas);
    }
    public function subpaginas()
    {
        if (!isset($_REQUEST["_urldir_"]))
            return array();
        $paginas = explode("/", $_REQUEST["_urldir_"]);
        array_shift($paginas);
        return $paginas;
    }
	private function ctrlsglob(){
		if(isset($this->controles["@global"]) && count($this->controles["@global"]) > 0){
			foreach($this->controles["@global"] as $ctrl){
				include_once $ctrl;
				$fn="ctrl_" . preg_replace("/\.php/","",basename($ctrl));
				if(function_exists($fn)){
					$fn($this);
				}
			}
		}
	}
    public function renderizar()
    {
		$this->ctrlsglob();
        $pagina_atual = $this->pagina_atual();
        $subdir       = $this->subpaginas();
        $evento       = false;
        if ($pagina_atual == "") {
            $pagina_atual = "home";
        }
        $pagina    = isset($this->paginas[$pagina_atual]) ? $this->paginas[$pagina_atual] : false;
        $subpagina = array_pop($subdir);
        while (!isset($this->subpaginas[$subpagina])):
            $subpagina = array_pop($subdir);
            if (!count($subdir)):
                break;
            endif;
        endwhile;
        if ($pagina && isset($this->subpaginas[$subpagina])) {
            $vetor_raiz = array(
                $pagina_atual
            );
            foreach ($subdir as $adicionar_raiz):
                $vetor_raiz[] = $adicionar_raiz;
            endforeach;
            $subpag       = $this->subpaginas[$subpagina];
            $raiz_correta = true;
            foreach ($subpag["raiz"] as $posic => $raiz):
                $raiz_correta = $raiz_correta && $vetor_raiz[$posic] == $raiz;
            endforeach;
            if ($raiz_correta):
                $pagina       = $subpag;
                $pagina_atual = $subpagina;
                $subpagina    = true;
            endif;
        } else {
            $subpagina = false;
        }
        if ($pagina && $pagina["interface"] == -1) {
            if (!file_exists($this->basedir() . "/interface/404.php")) {
                $interface = false;
            } else {
                $interface = $this->basedir() . "/interface/404.php";
            }
        } elseif ($pagina) {
            $interface = $this->basedir() . $pagina["interface"];
        } else {
            if (!file_exists($this->basedir() . "/interface/404.php")) {
                $interface = false;
            } else {
                $interface = $this->basedir() . "/interface/404.php";
            }
        }
        
        $scripts   = array_merge($this->scripts["@global"], (isset($this->scripts[$pagina_atual]) ? $this->scripts[$pagina_atual] : array()));
        $estilos   = array_merge($this->estilos["@global"], (isset($this->estilos[$pagina_atual]) ? $this->estilos[$pagina_atual] : array()));
        
        if ($pagina) {
            if ($pagina["modelo"] == -1) {
                $modelo = false;
            } else {
                $modelo = $this->basedir() . $pagina["modelo"];
            }
            if ($pagina["controle"] == -1) {
                $controle = false;
            } else {
                $controle = $this->basedir() . $pagina["controle"];
                if (!$interface) {
                    $evento = true;
                }
            }
            $controles = ((isset($this->controles[$pagina_atual]) ? $this->controles[$pagina_atual] : array()));
            if ($subpagina && isset($pagina["herdar"]) && is_array($pagina["herdar"])) {
                foreach ($pagina["herdar"] as $herdar):
                    $herdar = $pagina["raiz"][$herdar];
                    echo $herdar;
                    $scripts   = array_merge($scripts, (isset($this->scripts[$herdar]) ? $this->scripts[$herdar] : array()));
                    $estilos   = array_merge($estilos, (isset($this->estilos[$herdar]) ? $this->estilos[$herdar] : array()));
                    $controles = array_merge($controles, (isset($this->controles[$herdar]) ? $this->controles[$herdar] : array()));
                endforeach;
            } 
            foreach ($controles as $controleglobal) {
                if (file_exists($controleglobal)) {
                    include_once $controleglobal;
                }
            }
            if ($pagina && $controle) {
                include_once $controle;
                if (function_exists('controle')) {
                    controle($this);
                }
            }
            if ($pagina && $modelo) {
                include_once $modelo;
                if (function_exists('modelo')) {
                    modelo($this);
                }
            }
        }
        if ($interface) {
            include_once $interface;
            if (function_exists('layout')) {
                layout($this,$this->res2html($scripts, "js"), $this->res2html($estilos, "css"));
            }
        }  elseif (!$evento) {
            $fake = explode("/", $_REQUEST["_urldir_"]);
            $fake = $fake[count($fake)-1];
            if(preg_match("/\.map/",$fake)){
                header("Content-Type: application/json");
                $fake = preg_replace("/\.map/","",$fake);
                exit('{"version":1,"sources":["'.$fake.'"],"names":[],"mappings":";","file":"' . $fake . '"}');
            } 
        }
    }
}
?>