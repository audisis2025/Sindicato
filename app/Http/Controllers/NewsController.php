<?php
/*
* Nombre de la clase           : NewsController.php
* Descripción de la clase      : Controlador encargado de la gestión de noticias, comunicados, convocatorias y eventos del sindicato.
* Fecha de creación            : 27/09/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 14/12/2025
* Autorizó                     : Salvador Monroy
* Versión                      : 1.2
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        : 
* Descripción del mantenimiento: 
* Responsable                  :
* Revisor                      : 
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
			'publication_date' => 'nullable|date',
			'expiration_date' => 'nullable|date|after_or_equal:publication_date',
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
			'publication_date' => $validated['publication_date'] ?? null,
			'expiration_date' => $validated['expiration_date'] ?? null,
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
