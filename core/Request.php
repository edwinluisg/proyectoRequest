<?php
namespace core;

class Request
{
    public static $form;
    public static $errors;
    public static $errorsCont;

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

    public static function requerido($name, $alias, $parametro)
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


}