@section('content')
    <table>
        <tr>
            <td>SKU</td>
            <td>Last Updated</td>
            <td>In Stock QTY</td>
            <td>Price Matrix</td>
        </tr>
        @if (empty($data))
        <tr>
            <td colspan=4>No Data to Display</td>
        </tr>
        @endif
        @foreach ($data as $row)
        <tr>
            <td>{{ $row['sku'] }}</td>
            <td>{{ $row['last_updated'] }}</td>
            <td style="text-align:right">{{ $row['in_stock_quantity'] }}</td>
            <td>
            @if (!empty($row['prices']))
                @foreach ($row['prices'] as $price)
                    <div>&lt;{{ $price[0] }}: ${{ $price[1] }}</div>
                @endforeach
            @endif
            </td>
        </tr>
        @endforeach
    </table>
@stop

<html>
<head>
    <style type="text/css">
        table {
            border-collapse: collapse;
        }
        td {
            padding: 2px;
            border: 1px solid black;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
