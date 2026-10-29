<?php
/**
 * Cierra la sesión y vuelve al login.
 */
require __DIR__ . '/../../backend/bootstrap.php';

cerrar_sesion();
redirigir('vistas/login-signin/login.php');
