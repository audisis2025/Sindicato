<?php
/*
* ===========================================================
* Nombre de la clase: Controller
* Descripción de la clase: Controlador base del sistema, provee 
* funcionalidades de autorización y validación para el resto 
* de los controladores.
* Fecha de creación: 01/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 01/11/2025
* Autorizó: Líder Técnico
* Versión: 1.0
*
* Fecha de mantenimiento: [DD/MM/AAAA]
* Folio de mantenimiento: [Folio]
* Tipo de mantenimiento: [Correctivo/Perfectivo/Adaptativo/Preventivo]
* Descripción del mantenimiento: [Descripción breve del cambio]
* Responsable: [Tu Nombre]
* Revisor: [Revisor]
* ===========================================================
*/

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
	use AuthorizesRequests, ValidatesRequests;
}
