<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Services\SystemLogger;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Si este módulo es solo para sindicato:
        // $this->middleware('isUnion');
    }

    public function index()
    {
        $news = News::orderBy('id', 'desc')->get();
        return view('union.news.index', compact('news'));
    }

    public function create()
    {
        return view('union.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:convocatoria,comunicado,evento',
            'file_path' => 'nullable|string|max:255',
            'status' => 'required|in:borrador,publicada'
        ]);

        $news = News::create([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'type' => $validated['type'],
            'file_path' => $validated['file_path'] ?? null,
            'user_id' => Auth::id(),
            'status' => $validated['status'],
        ]);

        app(SystemLogger::class)->log(
            'Crear convocatoria',
            'El sindicato publicó la convocatoria: ' . $news->title
        );

        return redirect()->route('union.news.index')->with('success', 'Convocatoria creada correctamente.');
    }

    public function edit(News $news)
    {
        return view('union.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:convocatoria,comunicado,evento',
            'file_path' => 'nullable|string|max:255',
            'status' => 'required|in:borrador,publicada'
        ]);

        $news->update($validated);

        app(SystemLogger::class)->log(
            'Editar convocatoria',
            'El sindicato actualizó la convocatoria: ' . $news->title
        );

        return redirect()->route('union.news.index')->with('success', 'Convocatoria actualizada correctamente.');
    }

    public function destroy(News $news)
    {
        $titulo = $news->title;

        $news->delete();

        app(SystemLogger::class)->log(
            'Eliminar convocatoria',
            'El sindicato eliminó la convocatoria: ' . $titulo
        );

        return redirect()->route('union.news.index')->with('success', 'Convocatoria eliminada correctamente.');
    }
}
