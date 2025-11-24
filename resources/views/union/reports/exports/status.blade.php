<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte por estado</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #241178; color: white; }
    </style>
</head>
<body>

<h2>Completados vs Pendientes</h2>

<table>
    <thead>
        <tr>
            <th>Estado</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Completados</td>
            <td>{{ $data[1][1] }}</td>
        </tr>
        <tr>
            <td>Pendientes</td>
            <td>{{ $data[2][1] }}</td>
        </tr>
    </tbody>
</table>

</body>
</html>
