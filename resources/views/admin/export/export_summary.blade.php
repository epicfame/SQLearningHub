<table border="1" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <td>{{ ucfirst(__('title')) }}</td>
            <td></td>
            <td>: {{ strtoupper(__('Report Summary')) }}</td>
        </tr>
        <tr>
            <td>{{ ucfirst(__('Downloaded by')) }}</td>
            <td></td>
            <td>: {{ strtoupper($auth['email']) }}</td>
        </tr>
        <tr>
            <td>{{ ucfirst(__('Date downloaded')) }}</td>
            <td></td>
            <td>: {{ $date }}</td>
        </tr>
        <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Score</th>
            <th>Final Score</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($items as $row)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['email'] }}</td>
            <td>{{ $row['score'] }}</td>
            <td>{{ ($row['score']/$row['max_score']) * 100 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>