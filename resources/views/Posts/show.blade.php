@extends('layouts.app')

@section('content')
   <h1>Blog Posts Details</h1>
   <a href="{{url()->previous() }}">Back</a>
   <ul>
    <li>
        ID: {{ $post->id}} 
    </li>
    <li> Title: {{ $post->title}}</li>
    <li>Content: {{ $post->content}}</li>
   </ul>
     
@endsection