<?php
class Validations
{
    /**
     * @var array $patterns 
     */
    public $patterns = array(
        'email' => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]',
        'text'  => '[\p{L}0-9\s-.,;:!"%&()?+\'|°#\/@]+',
        'tel'   => '[0-9+\s()-]+',
        'int'   => '[0-9]+',
        'words' => '[\p{L}\s]+',
        'alphanum' => '[\p{L}0-9]+',
        'alpha' => '[\p{L   }]+',

    );
    public $errors = array();
    /**
     * Nombre del campo
     * @param string $name
     * @return $name
     */

    public $name = '';
    public $value = '';

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Capturar el valor del campo
     * @param string $value
     * @return $value
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }
    public function pattern($name)
    {
        if ($name == 'array') {
            if (!is_array($this->value)) {
                $this->errors[] = nl2br("Formato del campo " . $this->$name . " no es válido.\n");
            }
        } else {
            $regex = '/^(' . $this->patterns[$name] . ')$/u';
            if ($this->value != '' && !preg_match($regex, $this->value)) {
                $this->errors[] = nl2br('Formato del campo ' . $this->$name . " no es válido.\n");
            }
        }
        return $this;
    }
    /**
     * Patter personalizado
     * @param string $pattern
     * return $this
     * */
    public function customPattern($pattern)
    {
        $regex = '/^(' . $pattern . ')$/u';
        if ($this->value != '' && !preg_match($regex, $this->value)) {
            $this->errors[] = nl2br('Formato del campo ' . $this->name . " no es válido.\n");
        }
        return $this;
    }
    /**
     * Campos obligatorios
     * @return $this
     */
    public function required()
    {
        if ($this->value  == '' || $this->value == null) {
            $this->errors[] = nl2br("El campo " . $this->name . " es requerido.\n");
        }
        return $this;
    }
    /**
     * Validar el minimo de un campo
     * @param int $min
     * return $this
     */
    public function min($length)
    {
        if (is_string($this->value)) {
            if (strlen($this->value) < $length) {
                $this->errors[] = nl2br('El campo ' . $this->name . " es inferior al valor mínimo permitido.\n");
            }
        } else {
            if ($this->value < $length) {
                $this->errors[] = nl2br('El campo ' . $this->name . " es inferior al valor mínimo permitido.\n");
            }
        }
        return $this;
    }
    /**
     * Validar caracteres en campo maximo
     * @param int $max
     * return $this
     */
    public function max($length)
    {
        if (is_string($this->value)) {
            if (strlen($this->value) > $length) {
                $this->errors[] = nl2br('El campo ' . $this->name . " es mayor al valor máximo permitido.\n");
            }
        } else {
            if ($this->value < $length) {
                $this->errors[] = nl2br('El campo ' . $this->name . " es mayor al valor máximo permitido.\n");
            }
        }
        return $this;
    }
    /**
     * Valida si un campo es igual o no
     * @param mixed $value
     * return $this
     */
    public function equal($value)
    {
        if ($this->value != $value) {
            $this->errors[] = nl2br('El valor del campo ' . $this->name . " no coincide.\n");
        }
        return $this;
    }
    /**
     * Valida si un campo es de tipo int
     * @param mixed $value
     * return boolean 
     */
    public function is_int($value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            return true;
        }
    }
    /**
     * Verifica si un valor es string del alfabeto
     * @param mixed $value
     * return boolean 
     */
    public function is_alpha($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) {
            return true;
        }
    }
    /**
     * Verifica si un valor trae letras y numeros
     * @param mixed $value
     * return boolean 
     */
    public function is_alphanum($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) {
            return true;
        }
    }
    /**
     * Verifica si el valor es un correo
     * @param mixed $value
     * return boolean 
     */
    public function is_email($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
    }
    /**
     * Mostrando errores o exito
     */
    /**
     * Validado
     * @parama boolea
     */
    public function isSuccess()
    {
        if (empty($this->errors)) {
            return true;
        }
    }
    /**
     * Devolver errores
     * return arrray $this->errors|
     */
    public function getErrors()
    {
        if (!$this->isSuccess()) {
            return $this->errors;
        }
    }
    /**
     * Visualiza los errores en HTML
     * return string html
     */
    public function _displayErrors()
    {
        $html = '<ul>';
        foreach ($this->getErrors() as $error) {
            $html += '<li>' . $error . '</li>';
        }
        $html += '</ul>';
        return $html;
    }
    /**
     * Devuelve los errores en string puro
     * return boolean|string
     */
    public function result()
    {
        if (!$this->isSuccess()) {
            foreach ($this->getErrors() as $error) {
                echo "$error\n";
            }
            exit;
        } else {
            return true;
        }
    }
}
