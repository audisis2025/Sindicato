<?php

namespace App\Observers;

use App\Models\News;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class NewsObserver
{
    public function created(News $news): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'module'     => 'Noticias y convocatorias',
            'action'     => "Creó la publicación: {$news->title}",
            'ip_address' => request()->ip(),
        ]);
    }

    public function updated(News $news): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'module'     => 'Noticias y convocatorias',
            'action'     => "Actualizó la publicación: {$news->title}",
            'ip_address' => request()->ip(),
        ]);
    }

    public function deleted(News $news): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'module'     => 'Noticias y convocatorias',
            'action'     => "Eliminó la publicación: {$news->title}",
            'ip_address' => request()->ip(),
        ]);
    }
}
