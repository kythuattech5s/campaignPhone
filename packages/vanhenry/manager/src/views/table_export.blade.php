<table>
    <thead>
    <tr>
        <th>STT</th>
        @foreach($notes as $note)
        <th>{{$note}}</th>
        @endforeach        
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
        <tr>
            <td>{{$key + 1}}</td>
            @foreach($fields as $field)
            <td>{{ $item->$field }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>