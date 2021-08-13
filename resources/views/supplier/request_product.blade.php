<table class="table table-striped">
    <thead>
        <th>Nama</th>
        <th>Jumlah</th>
    </thead>
    <tbody>
        @foreach($products as $p)
        <tr>
            <td>{{ $p->name }}</td>
            <td>{{ $p->pivot->amount }}</td>
        </tr>
        @endforeach
    </tbody>
</table>