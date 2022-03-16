@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="text-black-50 mt-2">Blocked Users</h2>
        <table class="table table-striped" id="myTable">
            <thead>
            <tr>

                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userBlock as $item)
            <tr>
                <td scope="col"> {{$item->name}}</td>
                <td scope="col"> {{$item->type}} </td>
                <td scope="col">{{$item->email}} </td>
                <td scope="col">{{$item->phone_number}} </td>
                <td scope="col">
                    <a href="{{route('un-blocked-user',$item->id)}}"  class="btn btn-success">Un Block</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(".viewProfile").click(function() {
            var id = $(this).data('id');
            fecthdata(id)
        });

        function fecthdata(id) {
            $.ajax({
                type: 'GET',
                url: "{{ url('user') }}" + '/' + id,
                data: '_token = <?php echo csrf_token(); ?>',
                success: function(data) {
                    console.log(data);
                    $('#exampleModal .modal-body #name').val(data.name)
                    $('#exampleModal .modal-body #email').val(data.email)
                    $('#exampleModal .modal-body #type').val(data.type)
                    $('#exampleModal .modal-body #phone_number').val(data.phone_number)
                    $('#exampleModal .modal-body #city').val(data.city)
                    $('#exampleModal .modal-body #country').val(data.country)
                    $('#exampleModal .modal-body #grade').val(data.grade)
                    $('#exampleModal .modal-body #institue_name').val(data.institue_name)
                    $('#exampleModal .modal-body #status').val(data.id)
                    $('#exampleModal .modal-body #register').val(data.created_at)
                    $('#exampleModal').modal('show');
                }
            });
        }
    </script>
@endsection
@section('content')
    {{-- <script>
        $(document).ready(function() {
            $(document).ready(function() {
                var table = $('#myTable').DataTable();
            });
        });
    </script> --}}
@endsection
