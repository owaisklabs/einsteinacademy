@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="text-black-50 mt-2">Privacy Policy</h2>
        <form action="{{route('privacy-policy')}}" method="POST">
            @csrf
        <textarea name="privacy_policy" >
            {!! $privacyPolicy->description !!}
        </textarea>
        <div class="mt-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        </form>
    </div>
    <script>
        CKEDITOR.replace( 'privacy_policy' );
    </script>
@endsection
