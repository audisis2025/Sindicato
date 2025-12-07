<?php

namespace App\Http\Controllers;

use App\Models\News;

class WorkerNewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isWorker']);
    }

    /** LISTADO DE PUBLICACIONES */
    public function index()
    {
        $news_list = News::where('status', 'published')
            ->orderBy('publication_date', 'desc')
            ->get();

        return view('worker.news', compact('news_list'));
    }

    /** DETALLE DE UNA PUBLICACIÓN */
    public function show($id)
    {
        $news = News::where('status', 'published')->findOrFail($id);

        return view('worker.news_detail', compact('news'));
    }
}
