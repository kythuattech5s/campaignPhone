@extends('vh::master')
@section('content')
@if (isset($gaViewKey) && $gaViewKey !='')
    @include('vh::statistical_google_analytics')
@endif
@stop