<?php

/**
 * ===========================================================
 * Nombre de la clase: NewsController.php
 * Descripción: Controlador del módulo de Noticias y Convocatorias sindicales.
 * Fecha de creación: 03/11/2025
 * Elaboró: Iker Piza
 * Fecha de liberación: 03/11/2025
 * Autorizó: Líder Técnico
 * Versión: 1.0
 * Tipo de mantenimiento: Creación inicial.
 * Descripción del mantenimiento:
 *   Se crea el controlador base para gestionar la publicación,
 *   edición y administración de noticias y convocatorias sindicales.
 *   (Aún sin lógica funcional, solo estructura y rutas vinculadas a vistas).
 * Responsable: Iker Piza
 * Revisor: QA SINDISOFT
 * ===========================================================
 */

namespace App\Http\Controllers\Union;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * 📋 Muestra el listado de noticias, avisos y convocatorias.
     * Ruta: GET /union/news
     * Vista: resources/views/union/news/index.blade.php
     */
    public function index()
    {
        // 🔹 En el futuro: obtener registros desde la base de datos (modelo News)
        // $news_list = News::latest()->get();

        return view('union.news.index', [
            // 'news_list' => $news_list
        ]);
    }

    /**
     * 📝 Muestra el formulario para crear una nueva publicación.
     * Ruta: GET /union/news/create
     * Vista: resources/views/union/news/create.blade.php
     */
    public function create()
    {
        return view('union.news.create');
    }

    /**
     * 💾 Guarda una nueva noticia en la base de datos.
     * Ruta: POST /union/news
     * (Sin funcionalidad implementada aún)
     */
    public function store(Request $request)
    {
        // 🔸 Validación pendiente
        // 🔸 Guardar publicación con estado seleccionado

        return redirect()->route('union.news.index')
            ->with('success', 'Publicación creada correctamente (vista de prueba).');
    }

    /**
     * ✏️ Muestra el formulario de edición para una publicación existente.
     * Ruta: GET /union/news/{id}/edit
     * (Vista no implementada por el momento)
     */
    public function edit($id)
    {
        // $news = News::findOrFail($id);
        return view('union.news.edit');
    }

    /**
     * 🔄 Actualiza la información de una noticia.
     * Ruta: PUT /union/news/{id}
     * (Sin funcionalidad implementada aún)
     */
    public function update(Request $request, $id)
    {
        // $news = News::findOrFail($id);
        // $news->update($request->all());

        return redirect()->route('union.news.index')
            ->with('success', 'Publicación actualizada correctamente.');
    }

    /**
     * 🗑️ Elimina una publicación del sistema.
     * Ruta: DELETE /union/news/{id}
     */
    public function destroy($id)
    {
        // News::destroy($id);

        return redirect()->route('union.news.index')
            ->with('success', 'Publicación eliminada correctamente.');
    }
}
