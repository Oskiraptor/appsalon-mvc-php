<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Regresa T o F si es el ultomo
function esUltimo(string $actual, string $proximo) : bool {
    if ($actual !== $proximo) {
        return true;
    }
    return false;
}

// Funcion que revisa que el usuario este autenticado
function isAuth() : void {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

function isAdmin() {
    if (!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}