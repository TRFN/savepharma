<?php
    class transacao {
        public static function criar($entrada){
            $entrada = str_split(json_encode($entrada));

            $tabela1 = str_split('[]{}:,0123456789null"');
            $tabela2 = str_split("1234abcdef9ABCDEF5678");

            $tabela1 = array_flip($tabela1);

            for($i = 0; $i < count($entrada); $i++){
                $entrada[$i] = $tabela2[$tabela1[$entrada[$i]]];
            }
            // exit(print_r($entrada,true));
            return implode("", $entrada);
        }

        public static function ler($entrada){
            $entrada = str_split($entrada);

            $tabela1 = str_split('[]{}:,0123456789null"');
            $tabela2 = str_split("1234abcdef9ABCDEF5678");

            $tabela2 = array_flip($tabela2);

            for($i = 0; $i < count($entrada); $i++){
                $entrada[$i] = $tabela1[$tabela2[$entrada[$i]]];
            }

            $saida = json_decode(implode("", $entrada),true);

            return $saida;
        }
    }
?>
