<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tabla completa de solicitudes</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #241178; color: white; font-size: 12px; }
    </style>
</head>
<body>

<h2>Listado completo de solicitudes</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Trabajador</th>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Apertura</th>
            <th>Cierre</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $i => $req)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $req->user->name ?? '—' }}</td>
            <td>{{ $req->procedure->name ?? '—' }}</td>
            <td>{{ $req->procedure->description ?? '—' }}</td>
            <td>{{ $req->status ?? '—' }}</td>
            <td>{{ $req->procedure->opening_date ?? '—' }}</td>
            <td>{{ $req->procedure->closing_date ?? '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
