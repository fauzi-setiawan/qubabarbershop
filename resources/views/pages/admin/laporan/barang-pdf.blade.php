<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Barang</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; color: #C19A6B; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #C19A6B;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f8f8f8;
        }
    </style>
</head>
<body>
    <h2>Laporan Data Barang</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Brand</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->brand }}</td>
                <td>{{ $barang->stok }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
