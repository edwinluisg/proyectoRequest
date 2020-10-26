<?php
namespace core;

class Request
{
    public static $form;
    public static $errors;
    public static $errorsCont;

    // $reglas = [
    //     'nullable',//el campo puede ser null o vacio
    //     'required',//el campo es requerido
    //     'numeric',//el campo debe ser numero
    //     'integer',//el campo debe ser entero
    //     'double',//el campo puede ser con decimal
    //     'string',//el campo deber ser un texto normal
    //     'text',//el campo permite cualquier caracter
    //     'slug',//el campo debe cumplir como slug
    //     'email',//el campo debe ser email valido
    //     'date',//el campo debe ser formato fecha
    //     'timestamp',//el campo debe ser formato timestamp
    //     'image',//el campo dene ser imagen
    //     'image_required',//el campo requiere una imagen
    //     'min:numero',//el campo puede ser como minimo el numero
    //     'max:numero',//el campo puede ser como maximo el numero
    //     'long_min:numero',//el campo debe tener un larno minino de numero
    //     'long_max:numero',//el campo debe tener un larno maximo de numero
    //     'same:valor',//el campo debe ser igual a valor
    //     'age'//el campo debe ser una edad valida
    // ];

    public static function validar()
    {
        foreach (self::$form as $key => $value) {
            $datosNombres = explode("|", $key);
            $name = $datosNombres[0];
            $alias = $datosNombres[1];
            $datosReglas = explode("|", $value);
            foreach ($datosReglas as $regla) {
                $datosParametros = explode(":", $regla);
                $regla = $datosParametros[0];
                $parametro = null;
                if (count($datosParametros) > 1) {
                    $parametro = $datosParametros[1];
                }
                $resp = self::$regla($name, $alias, $parametro);
                if (!$resp) {
                    self::$errorsCont++;
                    break;
                } 
            }
        }

        if (self::$errorsCont > 0) {
            self::$errors['status'] = false;
            return false;
        } else {
            return true;
        }
    }

    public static function required($name, $alias, $parametro)
    {
        $value = trim($_REQUEST[$name]);
        $resp = false;
        $mensaje = '';
        if ($value != null AND $value != '') {
            $resp = true;
        }else{
            $mensaje = 'El campo ' . $alias . ' es obligatorio';
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function numeric($name, $alias, $parametro)
    {
        $value = trim($_REQUEST[$name]);
        $resp = false;
        $mensaje = '';
        if ($value != '') {
            if (is_numeric($value)) {
                $resp = true;
            } else {
                $mensaje = 'El campo ' . $alias . ' debe ser tipo numérico';
            }
        } else {
            $resp = true;
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function integer($name, $alias, $parametro)
    {
        $value = trim($_REQUEST[$name]);
        $resp = false;
        $mensaje = '';
        if ($value != '') {
            if (preg_match("/^([0-9])*$/", $value)) {
                $resp = true;
            } else {
                $mensaje = 'El campo ' . $alias . ' debe ser un número entero';
            }
        } else {
            $resp = true;
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function double($name, $alias, $parametro)
    {

    }

    public static function string($name, $alias, $parametro)
    {
        $value = trim($_REQUEST[$name]);
        $resp = false;
        $mensaje = '';
        if ($value != '') {
            if (preg_match("/^(?!-+)[a-zA-Z-ñáéíóú ]*$/", $value)) {
                $resp = true;
            } else {
                $mensaje = 'El campo ' . $alias . ' debe ser una cadena de texto';
            }
        } else {
            $resp = true;
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function text($name, $alias, $parametro)
    {

    }

    public static function slug($name, $alias, $parametro)
    {
        $value = trim($_REQUEST[$name]);
        $resp = false;
        $mensaje = '';
        if ($value != '') {
            $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_";
            $cont = true;
            for ($i=0; $i < strlen($value) ; $i++){
                $encuentra = strpos($permitidos, substr($value,$i,1));
                if ($encuentra === false){
                    $cont = false;
                    break;
                }
            }
            if ($cont) {
                $resp = true;
            } else {
                $mensaje = 'El campo ' . $alias . ' solo acepta letras, números, - (guión medio) , _ (guión bajo)';
            }
        } else {
            $resp = true;
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function email($name, $alias, $parametro)
    {
        $value = trim($_REQUEST[$name]);
        $resp = false;
        $mensaje = '';
        if ($value != '') {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $resp = true;
            } else {
                $mensaje = 'El campo ' . $alias . ' debe ser un correo correcto'; 
            }
        } else {
            $resp = true;
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function date($name, $alias, $parametro)
    {
        $value = trim($_REQUEST[$name]);
        $resp = false;
        $mensaje = '';
        if ($value != '') {
            $con = strlen($value);
            $valores = explode('-', $value);
            if (($con >= 8) AND ($con <= 10) AND (count($valores) == 3)) {
                if (checkdate($valores[1], $valores[2], $valores[0])) {
                    $resp = true;
                } else {
                    $mensaje = 'El campo ' . $alias . ' debe ser una fecha valida';
                }
            } else {
                $mensaje = 'El campo ' . $alias . ' debe tener el formato dd/mm/yyyy';
            }
        } else {
            $resp = true;
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function timestamp($name, $alias, $parametro)
    {

    }

    public static function image($name, $alias, $parametro)
    {
        $file = $_FILES[$name];
        $resp = false;
        $mensaje = '';
        if (isset($file)) {
            if ($file["error"] <= 0) {
                $extencion = pathinfo($file["name"])['extension'];
                $array = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
                foreach ($array as $value) {
                    if ($value == $extencion) {
                        $resp = true;
                        break;
                    }
                }
                if (!$resp) {
                    $mensaje = 'El campo ' . $alias . ' debe tener un formato de imagen correcto';
                }
            } else {
                $resp = true;
            }
        } else {
            $resp = true;
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function image_required($name, $alias, $parametro)
    {
        $file = $_FILES[$name];
        $resp = false;
        $mensaje = '';
        if (isset($file)) {
            if ($file["error"] <= 0) {
                $resp = true;
            }
        }
        if (!$resp) {
            $mensaje = 'El campo ' . $alias . ' es obligatorio';
        }
        self::$errors[$name] = $mensaje;
        return $resp;
    }

    public static function min($name, $alias, $parametro)
    {

    }

    public static function max($name, $alias, $parametro)
    {

    }

    public static function long_min($name, $alias, $parametro)
    {

    }

    public static function long_max($name, $alias, $parametro)
    {

    }

    public static function same($name, $alias, $parametro)
    {

    }

    public static function age($name, $alias, $parametro)
    {

    }

}