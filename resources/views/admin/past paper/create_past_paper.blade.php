@extends('layouts.app')
@section('content')
    <div class="card card-primary">
        <h2 class="text-black-50 mt-2 ml-3"> Create Past Papers</h2>
        <form method="POST" action="{{ route('past-paper.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @if($errors->any())
                <div class="mb-2 alert alert-danger" >
                    {{ implode('', $errors->all(':message')) }}
                </div>
        @endif
                <div class="form-group">
                    <label for="exampleInputEmail1">Past Paper Title</label>
                    <input type="text" required name="title" class="form-control" id="exampleInputEmail1"
                        placeholder="Past Paper Title">

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="exampleInputPassword1">Select Subject</label>
                        <select name="subject" required class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                            data-select2-id="1" tabindex="-1" aria-hidden="true">
                            <option disabled selected="selected" data-select2-id="3">Select Subject </option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"> {{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group  col-md-6">
                        <label for="exampleInputPassword1">Select Grade/Standard</label>
                        <select name="grade" required class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                            data-select2-id="1" tabindex="-1" aria-hidden="true">
                            <option disabled selected="selected" data-select2-id="3">Select Grade</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}"> {{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="customer_records myfull">
                            <input name="files[]" type="file" required value="name">
                            <a class="extra-fields-customer btn btn-primary" href="#">Add More</a>
                        </div>

                        <div class="customer_records_dynamic"></div>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <script>
        $('.extra-fields-customer').click(function() {
            $('.customer_records').clone().appendTo('.customer_records_dynamic');
            $('.customer_records_dynamic .customer_records').addClass('single remove');
            $('.single .extra-fields-customer').remove();
            $('.single').append('<a href="#" class="remove-field btn-remove-customer">Remove</a>');
            $('.customer_records_dynamic > .single').attr("class", "remove");

            $('.customer_records_dynamic input').each(function() {
                var count = 0;
                var fieldname = $(this).attr("name");
                $(this).attr('name', fieldname);
                count++;
            });

        });

        $(document).on('click', '.remove-field', function(e) {
            $(this).parent('.remove').remove();
            e.preventDefault();
        });
    </script>
    <style>
        .myfull {
            display: block;
            width: 100%;
        }

    </style>
@endsection
