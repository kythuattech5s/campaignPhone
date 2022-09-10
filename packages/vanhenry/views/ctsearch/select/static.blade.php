<select name="{{$name}}" class="select2" style="width:250px">
    @foreach($data as $item)
    <option value="{{$item['key']}}" {{$value == $item['key'] ? 'selected' : ''}}>{{$item[$lang.'_value']}}</option>
    @endforeach
</select>