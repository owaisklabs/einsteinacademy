@extends('layouts.app')
@section('content')
<div class="card card-primary mt-3">
    <div class="card-header">
        <h3 class="card-title">Push Notification</h3>
    </div>


    <form>
        <div class="card-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Title</label>
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Body</label>
                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection
