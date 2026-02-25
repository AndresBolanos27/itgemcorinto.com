<?php
// index.php

// Capturar la URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '/';

// Definir rutas
$routes = [
    // HOME
    '/' => 'App/dashboard.php',
    'dashboard' => 'App/dashboard.php',
    'dashboard_docente' => 'App/docentes.php',
    'dashboard_estudiante' => 'App/estudiantes.php',


    // LOGIN
    'login' => 'App/login/login.php',
    'logout' => 'App/login/logout.php',
    'procesar_login' => 'App/login/procesar_login.php',
    'restablecer_contraseña' => 'App/login/restablecer_contrasena.php',

    // USERS
    'admin' => 'App/admin/admin.php',
    'procesar_registro_admin' => 'App/admin/procesar_registro.php',
    'editar_admin' => 'App/admin/editar_admin.php',
    'borrar_admin' => 'App/admin/borrar_admin.php',
    'exportaradminexel' => 'App/admin/exportar_excel.php',
    'exportaradminpdf' => 'App/admin/exportar_pdf.php',

    //GRUPOS
    'grupos' => 'App/grupos/grupos.php',
    'procesar_registro_grupo' => 'App/grupos/procesar_registro_grupo.php',
    'editar_grupo' => 'App/grupos/editar_grupo.php',
    'borrar_grupo' => 'App/grupos/borrar_grupo.php',
    'exportargrupopdf' => 'App/grupos/exportargrupopdf.php',
    'exportargrupoexcel' => 'App/grupos/exportargrupoexcel.php',

    //Ciclo escolar
    'cicloescolar' => 'App/ciclo_escolar/ciclo_escolar.php',
    'procesar_cicloescolar' => 'App/ciclo_escolar/procesar_cicloescolar.php',
    'editar_cicloescolar' => 'App/ciclo_escolar/editar_cicloescolar.php',
    'borrar_cicloescolar' => 'App/ciclo_escolar/borrar_cicloescolar.php',

    //Categorias
    'categorias' => 'App/categorias/categorias.php',
    'procesar_categorias' => 'App/categorias/procesar_categorias.php',
    'editar_categorias' => 'App/categorias/editar_categorias.php',
    'borrar_categorias' => 'App/categorias/borrar_categorias.php',

    // Materias
    'materias' => 'App/materias/materias.php',
    'procesar_materias' => 'App/materias/procesar_materias.php',
    'editar_materias' => 'App/materias/editar_materias.php',
    'borrar_materias' => 'App/materias/borrar_materias.php',
    'add_materias' => 'App/materias/add_materias.php',
    'procesar_asignacion_materias' => 'App/materias/procesar_asignacion_materias.php',
    'obtener_materias' => 'App/materias/obtener_materias.php',
    'borrar_asig_materias' => 'App/materias/borrar_asig_materias.php',

    // Docentes
    'docentes' => 'App/docentes/docentes.php',
    'procesar_docentes' => 'App/docentes/procesar_docentes.php',
    'editar_docentes' => 'App/docentes/editar_docentes.php',
    'borrar_docentes' => 'App/docentes/borrar_docentes.php',
    'add_docentes' => 'App/docentes/add_docentes.php',
    'guardar_asignacion' => 'App/docentes/guardar_asignacion.php',



    //Estudiantes
    'estudiantes' =>  'App/estudiantes/estudiantes.php',
    'procesar_estudiante' => 'App/estudiantes/procesar_estudiante.php',
    'editar_estudiante' => 'App/estudiantes/editar_estudiante.php',
    'borrar_estudiantes' => 'App/estudiantes/borrar_estudiantes.php',
    'obtener_estudiantes' => 'App/estudiantes/obtener_estudiantes.php',
    'obtener_estudiantes_materias' => 'App/estudiantes/obtener_estudiantes_materias.php',

    //Calificaciones
    'calificaciones' => 'App/calificaciones/calificaciones.php',
    'guardar_notas' => 'App/calificaciones/guardar_notas.php',
    'notas_docentes' => 'App/calificaciones/notas_docentes.php',
    'notas_estudiantes' => 'App/calificaciones/notas_estudiantes.php',



    // Config
    'empresa' => 'App/config/empresa.php',



];

// Manejar la solicitud
if (array_key_exists($url, $routes)) {
    include $routes[$url];
} else {
    include '404.php'; // Archivo de error 404
}
