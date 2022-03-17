@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="text-black-50 mt-2">Terms and Conditions</h2>
        <form action="{{route('terms-and-condition')}}" method="POST">
            @csrf
        <textarea class="mt-4" name="terms_and_condition"  >
            {!! $term_condition->description !!}
        </textarea>
        <div class="mt-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        </form>
    </div>
    <script>
        CKEDITOR.replace( 'terms_and_condition' );
    </script>
@endsection
