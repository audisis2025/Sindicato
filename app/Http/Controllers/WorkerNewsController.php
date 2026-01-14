<?php
/*
* Nombre de la clase           : WorkerNewsController.php
* Descripción de la clase      : Controlador encargado de la visualización de noticias, comunicados y eventos publicados para los trabajadores, aplicando filtros por fecha, tipo y palabra clave.
* Fecha de creación            : 20/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 19/12/2025
* Autorizó                     :
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        :
* Descripción del mantenimiento:
* Responsable                  :
* Revisor                      :
*/

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;
use Carbon\Carbon;

class WorkerNewsController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth', 'isWorker']);
	}

	public function index(): View
	{
		$today = \Carbon\Carbon::today('America/Mexico_City')->toDateString();

		$news_list = News::query()
			->where('status', 'published')
			->whereDate('publication_date', '<=', $today)
			->where(function ($q) use ($today)
			{
				$q->whereNull('expiration_date')
				->orWhereDate('expiration_date', '>=', $today);
			})
			->when(request('type'), function ($q, $type)
			{
				$q->where('type', $type);
			})
			->when(request('keyword'), function ($q, $keyword)
			{
				$keyword = trim($keyword);

				$q->where(function ($qq) use ($keyword)
				{
					$qq->where('title', 'like', "%{$keyword}%")
					->orWhere('content', 'like', "%{$keyword}%");
				});
			})
			->orderBy('publication_date', 'desc')
			->get();

		return view('worker.news', compact('news_list'));
	}

	public function show(string $id): View
	{
		$today = Carbon::today('America/Mexico_City')->toDateString();

		$news = News::query()
			->where('status', 'published')
			->whereDate('publication_date', '<=', $today)
			->where(function ($q) use ($today)
			{
				$q->whereNull('expiration_date')
				  ->orWhereDate('expiration_date', '>=', $today);
			})
			->findOrFail($id);

		return view('worker.news_detail', compact('news'));
	}
}