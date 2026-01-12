<?php
/*
* ===========================================================
* Nombre de la clase: NewsController
* Descripción de la clase: Gestiona el CRUD de noticias y
* comunicados del Sindicato, incluyendo carga de imágenes
* y documentos PDF.
* Fecha de creación: 03/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: [DD/MM/AAAA]
* Folio de mantenimiento: [Folio]
* Tipo de mantenimiento: [Correctivo/Perfectivo/Adaptativo/Preventivo]
* Descripción del mantenimiento: [Descripción breve]
* Responsable: [Tu Nombre]
* Revisor: [Revisor]
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\SystemLogger;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NewsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(): View
	{
		$news_list = News::orderBy('id', 'desc')->get();

		return view('union.news.index', compact('news_list'));
	}

	public function create(): View
	{
		return view('union.news.create');
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'nullable|string',
			'type' => 'required|in:announcement,communication,event',
			'status' => 'required|in:draft,published',
			'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
			'attachment' => 'nullable|file|mimes:pdf|max:8192',
		]);

		$imagePath = null;
		if ($request->hasFile('image'))
		{
			$imagePath = $request->file('image')->store('news_images', 'public');
		}

		$filePath = null;
		if ($request->hasFile('attachment'))
		{
			$filePath = $request->file('attachment')->store('news_files', 'public');
		}

		$news = News::create([
			'title' => $validated['title'],
			'content' => $validated['content'] ?? null,
			'type' => $validated['type'],
			'status' => $validated['status'],
			'image_path' => $imagePath,
			'file_path' => $filePath,
			'publication_date' => $request->publication_date,
			'expiration_date' => $request->expiration_date,
			'user_id' => Auth::id(),
		]);

		app(SystemLogger::class)->log(
			'Crear noticia',
			'El sindicato publicó: ' . $news->title
		);

		return redirect()
			->route('union.news.index')
			->with('success', 'Noticia/convocatoria creada correctamente.');
	}

	public function edit(News $news): View
	{
		return view('union.news.edit', compact('news'));
	}

	public function update(Request $request, News $news): RedirectResponse
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'nullable|string',
			'type' => 'required|in:announcement,communication,event',
			'status' => 'required|in:draft,published',
			'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
			'attachment' => 'nullable|file|mimes:pdf|max:8192',
		]);

		if ($request->hasFile('image'))
		{
			if ($news->image_path)
			{
				Storage::disk('public')->delete($news->image_path);
			}

			$news->image_path = $request->file('image')->store('news_images', 'public');
		}

		if ($request->hasFile('attachment'))
		{
			if ($news->file_path)
			{
				Storage::disk('public')->delete($news->file_path);
			}

			$news->file_path = $request->file('attachment')->store('news_files', 'public');
		}

		$news->update([
			'title' => $validated['title'],
			'content' => $validated['content'] ?? null,
			'type' => $validated['type'],
			'status' => $validated['status'],
			'publication_date' => $request->publication_date,
			'expiration_date' => $request->expiration_date,
		]);

		app(SystemLogger::class)->log(
			'Editar noticia',
			'El sindicato actualizó: ' . $news->title
		);

		return redirect()
			->route('union.news.index')
			->with('success', 'Noticia actualizada correctamente.');
	}

	public function destroy(News $news): RedirectResponse
	{
		if ($news->image_path)
		{
			Storage::disk('public')->delete($news->image_path);
		}

		if ($news->file_path)
		{
			Storage::disk('public')->delete($news->file_path);
		}

		$title = $news->title;

		$news->delete();

		app(SystemLogger::class)->log(
			'Eliminar noticia',
			'El sindicato eliminó la noticia: ' . $title
		);

		return redirect()
			->route('union.news.index')
			->with('success', 'Noticia eliminada correctamente.');
	}
}
