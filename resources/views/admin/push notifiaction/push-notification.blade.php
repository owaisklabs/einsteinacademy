@extends('layouts.app')
@section('content')
<div class="card card-primary mt-3">
    <div class="card-header">
        <h3 class="card-title">Push Notification</h3>
    </div>


    <form method="POST" action="{{route('push-notification.store')}}">
        @csrf()
        <div class="card-body">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" required class="form-control" id="title" placeholder="Title of Notification">
            </div>
            <div class="form-group">
                <label for="body">Body</label>
                <textarea type="text" name="body" required class="form-control" id="body" placeholder="Body of Notification">
                </textarea>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection
