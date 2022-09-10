@extends('index')
@section('main')
    @include('image_loader.tiny', ['itemImage' => $media, 'keyImage' => 'extra'])

    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" value="">
        <button>Lưu</button>
    </form>
@endsection
