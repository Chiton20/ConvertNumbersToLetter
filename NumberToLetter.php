<?php
/**
 * Created by PhpStorm.
 * User: magarcia
 * Date: 05/06/15
 * Time: 12:40
 *
 * Clase para convertir una cantidad numerica a letras.
 */

class NumberToLetter{
    private $moneda;

    function __construct($tipoMnda = null){
        $this->setMoneda($tipoMnda);
    }

    private function setMoneda($mnda){
        switch($mnda){
            case 'EUR':
                $this->moneda = Array('country' => 'Europa', 'currency' => 'EUR', 'singular' => 'EURO', 'plural' => 'EUROS', 'symbol', '€');
                break;
            case 'USD':
                $this->moneda = Array('country' => 'Estados Unidos', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$');
                break;
            case 'MXN':
                $this->moneda = Array('country' => 'México', 'currency' => 'MXN', 'singular' => 'PESO', 'plural' => 'PESOS', 'symbol', '$');
                break;
            default:
                $this->moneda = Array('singular' => '', 'plural' => '');
        }
    }

    private function Unidades($num){
        switch($num){
            case 1: return "UN";
            case 2: return "DOS";
            case 3: return "TRES";
            case 4: return "CUATRO";
            case 5: return "CINCO";
            case 6: return "SEIS";
            case 7: return "SIETE";
            case 8: return "OCHO";
            case 9: return "NUEVE";
        }

        return "";
    }

    private function Decenas($num){

        $decena = floor($num/10);
        $unidad = $num - ($decena * 10);

        switch($decena){
            case 1:
                switch($unidad){
                    case 0: return "DIEZ";
                    case 1: return "ONCE";
                    case 2: return "DOCE";
                    case 3: return "TRECE";
                    case 4: return "CATORCE";
                    case 5: return "QUINCE";
                    default: return "DIECI" . $this->Unidades($unidad);
                }
            case 2:
                switch($unidad){
                    case 0: return "VEINTE";
                    default: return "VEINTI" . $this->Unidades($unidad);
                }
            case 3: return $this->DecenasY("TREINTA", $unidad);
            case 4: return $this->DecenasY("CUARENTA", $unidad);
            case 5: return $this->DecenasY("CINCUENTA", $unidad);
            case 6: return $this->DecenasY("SESENTA", $unidad);
            case 7: return $this->DecenasY("SETENTA", $unidad);
            case 8: return $this->DecenasY("OCHENTA", $unidad);
            case 9: return $this->DecenasY("NOVENTA", $unidad);
            case 0: return $this->Unidades($unidad);
        }
    }

    private function DecenasY($strSin,$numUnidades){
        if ($numUnidades > 0){
            return $strSin . " Y " . $this->Unidades($numUnidades);
        }

        return $strSin;
    }

    private function Centenas($num){

        $centenas = floor($num / 100);
        $decenas = $num - ($centenas * 100);

        switch($centenas){
            case 1:
                if ($decenas > 0){
                    return "CIENTO " . $this->Decenas($decenas);
                }
                return "CIEN";
            case 2: return "DOSCIENTOS " . $this->Decenas($decenas);
            case 3: return "TRESCIENTOS " . $this->Decenas($decenas);
            case 4: return "CUATROCIENTOS " . $this->Decenas($decenas);
            case 5: return "QUINIENTOS " . $this->Decenas($decenas);
            case 6: return "SEISCIENTOS " . $this->Decenas($decenas);
            case 7: return "SETECIENTOS " . $this->Decenas($decenas);
            case 8: return "OCHOCIENTOS " . $this->Decenas($decenas);
            case 9: return "NOVECIENTOS " . $this->Decenas($decenas);
        }

        return $this->Decenas($decenas);
    }

    private function Seccion($nmro, $divisor, $strSingular, $strPlural){

        $cientos = floor($nmro/$divisor);
        $resto = $nmro - ($cientos * $divisor);
        $letras = "";

        if ($cientos > 0){
            if ($cientos > 1){
                $letras = $this->Centenas($cientos) . " " . $strPlural;
            }else{
                $letras = (($divisor == 1000)?($strPlural):($strSingular));
            }
        }

        if ($resto > 0){
            $letras .= "";
        }

        return $letras;
    }

    private function Miles($nmroMles){
        $cdnaNmro = "";
        $divisor = 1000;
        $miles = floor($nmroMles / $divisor);
        $resto = $nmroMles - ($miles * $divisor);

        if($miles > 0){
            $cdnaNmro = $this->Seccion($nmroMles, $divisor, "UN MIL", "MIL");
        }

        if($resto > 0){
            $cdnaNmro .= " ".$this->Centenas($resto);
        }

        return $cdnaNmro;
    }

    private function Millones($NmroMlln){
        $cdnaNmro = "";
        $divisor = 1000000;
        $millones = floor($NmroMlln / $divisor);
        $resto = $NmroMlln - ($millones * $divisor);

        if($millones > 0){
            $cdnaNmro = $this->Seccion($NmroMlln, $divisor, "UN MILLON", "MILLONES");
        }

        if($resto > 0){
            $cdnaNmro .= " ".$this->Miles($resto);
        }

        return $cdnaNmro;
    }

    public function NumeroALetras($num){
        $strNmro = "";
        $enteros = floor($num);
        $centavos = (((round($num * 100)) - (floor($num) * 100)));
        $letrasCentavos = "";

        if ($centavos > 0){
            $letrasCentavos = "CON ".$this->Decenas($centavos)." CENTAVOS";
        }

        if($enteros == 0){
            $strNmro =  "CERO " . $this->moneda['']." ".$letrasCentavos;
        }
        if ($enteros == 1){
            $strNmro = $this->Millones($enteros)." ".$this->moneda['singular']." ".$letrasCentavos;
        }else{
            $strNmro = $this->Millones($enteros)." " .$this->moneda['plural']." ".$letrasCentavos;
        }

        return utf8_decode($strNmro);
    }
}

function f_convertNmro($nmro,$mnda = null){
    $convert =  new NumberToLetter($mnda);

    return $convert->NumeroALetras($nmro);
}

if(isSet($_GET['nmro']) && isSet($_GET['mnda'])){
    echo f_convertNmro($_GET['nmro'],$_GET['mnda']);
}
?>
