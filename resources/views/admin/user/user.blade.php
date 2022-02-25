@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="text-black-50 mt-2">User Mangement</h2>
        <table class="table table-striped" id="myTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">City</th>
                    <th scope="col">Country</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Institute</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user as $item)
                    <tr>

                        <th scope="row">{{ $loop->index + 1 }}</th>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->city }}</td>
                        <td>{{ $item->country }}</td>
                        <td>{{ @$item->grade->name }}</td>
                        <td>{{ $item->institue_name }}</td>
                        <td>

                            <!-- actions -->
                            <!-- View Profile -->
                            <a href="#" class="viewProfile" data-id="{{ $item->id }}">
                                <i class="fas fa-user green ml-1"></i>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="container-fluid">
                            <div class="row">
                                <img src="https://flatlogic.github.io/light-blue-vue-admin/img/a5.84f014f0.jpg" style="height: 234px;" class="rounded mx-auto d-block" alt="...">
                            <div class="form-group col-6">
                                <label for="recipient-name" class=" col-form-label">Name:</label>
                                <input type="text" class="form-control" readonly id="name">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">Email:</label>
                                <input type="text" class="form-control" readonly id="email">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">Type:</label>
                                <input type="text" class="form-control" readonly id="type">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">Phone Number:</label>
                                <input type="text" class="form-control" readonly id="phone_number">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">City:</label>
                                <input type="text" class="form-control" readonly id="city">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">Country:</label>
                                <input type="text" class="form-control" readonly id="country">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">Grade:</label>
                                <input type="text" class="form-control" readonly id="grade">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">Institue Name:</label>
                                <input type="text" class="form-control" readonly id="institue_name">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">Status</label>
                                <input type="text" class="form-control" readonly id="status">
                            </div>
                            <div class="form-group col-6">
                                <label for="message-text" class="col-form-label">Register at</label>
                                <input type="text" class="form-control" readonly id="register">
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
