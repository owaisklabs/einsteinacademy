@extends('layouts.app')
@section('content')
<h2 class="text-black-50 mt-3 ml-3">Past Papers Details</h2>
<table class="table table-striped" id="myTable">
    <thead>
        <tr class="text-center">
            <th scope="col">Title</th>
            <th scope="col">Subject</th>
            <th scope="col">Grade/Subject</th>
            <th scope="col">Rating</th>
            <th scope="col">Created At</th>
        </tr>
    </thead>
    <tbody class="text-center">

        <tr>

            <td scope="row">{{$past_paper->title}}</td>
            <td>{{$past_paper->subject->name}}</td>
            <td>{{$past_paper->grade->name}}</td>
            <td></td>
            <td>{{$past_paper->grade->created_at}}</td>

        </tr>

    </tbody>
</table>
<h5 class="text-black-50 mt-3 ml-3">Media</h5>
<div class="container mt-4">
    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>File Name</th>
                <th>File</th>
                <th >Upload at</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ( $past_paper->Medias as $item)

            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$item->name}}</td>
                <td><a href="{{$item->path}}" target="_blank">View</a></td>
                <td>{{$item->created_at}}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
