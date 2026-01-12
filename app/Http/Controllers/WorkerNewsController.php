<?php
/*
* ===========================================================
* Nombre de la clase: WorkerNewsController
* Descripción de la clase: Muestra al trabajador las noticias
* publicadas por el Sindicato.
* Fecha de creación: 08/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 1.0
*
* Fecha de mantenimiento: [DD/MM/AAAA]
* Folio de mantenimiento: [Folio]
* Tipo de mantenimiento: [Correctivo/Perfectivo/Adaptativo/Preventivo]
* Descripción del mantenimiento: Estándar PRO-Laravel aplicado.
* Responsable: [Tu Nombre]
* Revisor: QA SINDISOFT
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;

class WorkerNewsController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth', 'isWorker']);
	}

	public function index(): View
	{
		$news_list = News::where('status', 'published')
			->orderBy('publication_date', 'desc')
			->get();

		return view('worker.news', compact('news_list'));
	}

	public function show(string $id): View
	{
		$news = News::where('status', 'published')
			->findOrFail($id);

		return view('worker.news_detail', compact('news'));
	}
}
