{{-- ===========================================================
 Nombre de la vista: exports.blade.php
 Descripción: Plantilla para exportación institucional de reportes.
 Fecha de creación: 04/11/2025
 Elaboró: Iker Piza
 Versión: 1.0
=========================================================== --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Sindical</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        h1 { color: #241178; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
        th { background: #f3f3f3; color: #DC6601; }
    </style>
</head>
<body>
    <h1>Reporte de Trámites Sindicales</h1>
    <p><strong>Generado por:</strong> {{ auth()->user()->name }}</p>
    <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}</p>

    @if(!empty($filters['from']) && !empty($filters['to']))
        <p><strong>Rango:</strong> {{ $filters['from'] }} al {{ $filters['to'] }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Trabajador</th>
                <th>Tipo de Trámite</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($solicitudes as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->trabajador->nombre ?? '—' }}</td>
                    <td>{{ $s->tipo_tramite }}</td>
                    <td>{{ ucfirst($s->estado) }}</td>
                    <td>{{ $s->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay registros con los filtros aplicados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
