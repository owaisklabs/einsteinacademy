@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex spacearound">
            <h2 class="text-black-50 mt-2">Past Papers</h2>

            <a class="btn btn-app bg-success mt-4" href="{{route('past-paper.create')}}">
                <i class="fas fa-plus"></i> Add Past Paper
            </a>
        </div>
        <table class="table table-striped" id="myTable">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Grade/Subject</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($past_papers as $past_paper)
                <tr>

                    <th scope="row"> {{$loop->index+1}}</th>
                    <td>{{$past_paper->title}}</td>
                    <td>{{$past_paper->subject->name}}</td>
                    <td>{{$past_paper->grade->name}}</td>
                    <td>

                        <!-- actions -->
                        <!-- View Profile -->
                        <a href="{{route('past-paper.show',$past_paper->id)}}" class="viewProfile">
                            <i class="fas fa-eye green ml-1"></i>
                        </a>
                        <!-- Edit -->
                        <a href="#" class="editButton">
                            <i class="fas fa-edit blue ml-1"></i>
                        </a>
                        <!-- Delete -->
                        <a href="#" class="deleteButton">
                            <i class="fas fa-trash red ml-1"></i>
                        </a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <style>
        .spacearound{
            justify-content: space-between;
            align-items: center;
        }
        .swal-button{
            background-color: #186429 !important;
        }

    </style>
    <script>
        @if (Session::has('past-paper-add'))
        swal("Past paper Add", "Past paper Add Successfully", "success");
        @endif
    </script>
@endsection
