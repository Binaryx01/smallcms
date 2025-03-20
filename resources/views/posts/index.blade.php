@extends('layouts.app')

@section('content')

<div class="container">


welcome to the index page.....

@foreach ( $posts as $post )



<h1>{{$post->title}}</h1>
<h4>{{$post->content}}</h4>
<p>{{$post->summary}}</p>
<hr>


@endforeach

</div>

<div class="d-flex justify-content-center">
    {{ $posts->links('pagination::bootstrap-5') }}
</div>


@endsection
