<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte por tipo de trámite</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #241178; color: white; }
    </style>
</head>
<body>

<h2>Trámites por tipo</h2>

<table>
    <thead>
        <tr>
            <th>Tipo de trámite</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $name => $total)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ $total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
