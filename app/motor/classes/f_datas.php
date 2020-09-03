<?php
    class f_datas {
        public static function diferenca($data1,$data2){
            $data1 = explode("/", $data1);
            $data1 = "{$data1[2]}-{$data1[1]}-{$data1[0]}";

            $data2 = explode("/", $data2);
            $data2 = "{$data2[2]}-{$data2[1]}-{$data2[0]}";

            $c = 0;
            foreach(new DatePeriod(
                new DateTime($data1), // 1st PARAM: start date
                new DateInterval('P1D'), // 2nd PARAM: interval (1 day interval in this case)
                new DateTime($data2), // 3rd PARAM: end date
                null // 4th PARAM (optional): self-explanatory
            ) as $_c): $c++; endforeach;

            return $c;
        }

        public static function somar($data, $dias, $meses = 0, $anos = 0){
            $diasmes = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

            if($data == -1): $data = date("d/m/Y"); endif;

            $data = explode("/", $data);

            $data[0] = (int)$data[0];
            $data[1] = (int)$data[1];
            $data[2] = (int)$data[2];

            $data[2] += (int)$anos;
            $data[0] += $dias;

            while($data[0] > $diasmes[$data[1]-1]){
                $data[0] -= $diasmes[$data[1]-1];
                $data[1]++;

                if($data[1] > 12){
                    $data[1] = 1;
                    $data[2]++;
                }
            }

            $data[1] += $meses;

            while($data[1] > 12){
                $data[1] -= 12;
                $data[2]++;
            }

            return implode("/", $data);

        }
    }
?>
