{{-- Tabla para PDF: Distribución por género --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte por género</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #241178; color: white; }
    </style>
</head>
<body>

<h2>Distribución por género</h2>

<table>
    <thead>
        <tr>
            <th>Género</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Hombres</td>
            <td>{{ $data[1][1] }}</td>
        </tr>
        <tr>
            <td>Mujeres</td>
            <td>{{ $data[2][1] }}</td>
        </tr>
        <tr>
            <td>No definido</td>
            <td>{{ $data[3][1] }}</td>
        </tr>
        <tr>
            <td>Prefiero no decirlo</td>
            <td>{{ $data[4][1] }}</td>
        </tr>
    </tbody>
</table>

</body>
</html>
