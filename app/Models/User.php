<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\News;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos asignables en masa.
     */
    protected $fillable = [
        'usuario',
        'name',
        'email',
        'password',
        'curp',
        'rfc',
        'sexo',
        'clave_presupuestal',
        'rol',
        'activo',
    ];

    /**
     * Campos ocultos al serializar el modelo.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipos de datos convertidos automáticamente.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
    ];
    public function initials(): string
    {
        $name = $this->name ?? '';
        return collect(explode(' ', $name))
            ->filter()
            ->take(2)
            ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }
    public function detalle()
    {
        return $this->hasOne(UsuarioDetalle::class, 'user_id');
    }
    public function showNews()
    {
        $news = News::orderBy('created_at', 'desc')->get();
        return view('worker.news', compact('news'));
    }
}
