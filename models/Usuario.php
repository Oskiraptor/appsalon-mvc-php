<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de vakidacion para crear una cuenta
    public function validarNuevaCuenta() {
        if (trim($this->nombre) === '') {
            self::$alertas['error'][] = 'El Nombre es obligatorio';
        }
        if (trim($this->apellido) === '') {
            self::$alertas['error'][] = 'El Apellido es obligatorio';
        }
        if (trim($this->email) === '') {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }
        if (trim($this->password) === '') {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener almenos 6 caracteres';
        }
        if (trim($this->telefono) === '') {
            self::$alertas['error'][] = 'El Telefono es obligatorio';
        }
        // Expresion regular para validar el Telefono
        if (!preg_match( '/[0-9]{10}/', $this->telefono)) {
            self::$alertas['error'][] = 'Formato no valido';
        }
        return self::$alertas;
    }

    // Validar el Login
    public function validarLogin() {
        if (trim($this->email) === '') {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if (trim($this->password) === '') {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        return self::$alertas;
    }

    // Validar el Email
    public function validarEmail() {
        if (trim($this->email) === '') {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        return self::$alertas;
    }

    // Validar Password
    public function validarPassword() {
        if (trim($this->password) === '') {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if (strlen($this->password) <6) {
            self::$alertas['error'][] = 'El password debe tener almenos 6 caracteres';
        }
        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El usuario ya esta resgistrado';
        }
        return $resultado;
    }

    // Hashear Password
    public function hashPassword() {
        
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

    }

    // Creando Token 
    public function crearToken() {
        $this->token = uniqid();
    }

    // Comprobar el password y verificandolo
    public function comprobarPasswordAndVerificado($password) {

        $resultado = password_verify($password, $this->password);
        
        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }

    }


}