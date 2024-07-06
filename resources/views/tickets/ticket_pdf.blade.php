<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thông tin Ticket</title>
    <style>
        /* Định dạng CSS cho PDF */
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Thông tin vé xe</h1>
    <table>
        <tr>
            <th>ID</th>
            <td>{{ $ticket->id }}</td>
        </tr>
        <tr>
            <th>Tầng</th>
            <td>{{ $ticket->floor_title }}</td>
        </tr>
        <tr>
            <th>Khu vực</th>
            <td>{{ $ticket->area->title }}</td>
        </tr>
        <tr>
            <th>Biển số xe</th>
            <td>{{ $ticket->licensePlate }}</td>
        </tr>
    </table>
</body>
</html>
