<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\SystemLogger;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('isUnion');
    }

    /**
     * Listado de todas las noticias.
     */
    public function index()
    {
        $news = News::orderBy('id', 'desc')->get();
        return view('union.news.index', compact('news'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('union.news.create');
    }

    /**
     * Guarda una nueva noticia
     * (TEXTO + IMAGEN + ARCHIVO PDF OPCIONAL)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'content'         => 'nullable|string',
            'type'            => 'required|in:convocatoria,comunicado,evento',
            'status'          => 'required|in:draft,published',

            // 🖼 Imagen de portada
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            // 📄 Archivo PDF opcional
            'attachment'      => 'nullable|file|mimes:pdf|max:8192',
        ]);

        // ===================== SUBIDA DE ARCHIVOS =====================

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('news_files', 'public');
        }

        // ===================== CREACIÓN =====================

        $news = News::create([
            'title'       => $validated['title'],
            'content'     => $validated['content'] ?? null,
            'type'        => $validated['type'],
            'status'      => $validated['status'],
            'image_path'  => $imagePath,
            'file_path'   => $filePath,
            'user_id'     => Auth::id(),
        ]);

        app(SystemLogger::class)->log(
            'Crear noticia',
            'El sindicato publicó: ' . $news->title
        );

        return redirect()
            ->route('union.news.index')
            ->with('success', 'Noticia/convocatoria creada correctamente.');
    }

    /**
     * Formulario de edición.
     */
    public function edit(News $news)
    {
        return view('union.news.edit', compact('news'));
    }

    /**
     * Actualiza una noticia existente.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'nullable|string',
            'type'        => 'required|in:convocatoria,comunicado,evento',
            'status'      => 'required|in:draft,published',

            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'attachment'  => 'nullable|file|mimes:pdf|max:8192',
        ]);

        // ===================== ACTUALIZAR ARCHIVOS =====================

        if ($request->hasFile('image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            $news->image_path = $request->file('image')->store('news_images', 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($news->file_path) {
                Storage::disk('public')->delete($news->file_path);
            }
            $news->file_path = $request->file('attachment')->store('news_files', 'public');
        }

        $news->update([
            'title'   => $validated['title'],
            'content' => $validated['content'] ?? null,
            'type'    => $validated['type'],
            'status'  => $validated['status'],
        ]);

        app(SystemLogger::class)->log(
            'Editar noticia',
            'El sindicato actualizó: ' . $news->title
        );

        return redirect()
            ->route('union.news.index')
            ->with('success', 'Noticia actualizada correctamente.');
    }

    /**
     * Eliminar noticia.
     */
    public function destroy(News $news)
    {
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }
        if ($news->file_path) {
            Storage::disk('public')->delete($news->file_path);
        }

        $title = $news->title;
        $news->delete();

        app(SystemLogger::class)->log(
            'Eliminar noticia',
            'El sindicato eliminó la noticia: ' . $title
        );

        return redirect()->route('union.news.index')
            ->with('success', 'Noticia eliminada correctamente.');
    }
}
