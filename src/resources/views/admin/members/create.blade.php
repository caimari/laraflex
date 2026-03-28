@extends('laraflex::layouts.admin')

@section('content')

<script>
    // Toggle password visibility
    $(document).on('click', '#toggle-password', function() {
        var passwordField = $('#password');
        var passwordFieldType = passwordField.attr('type');
        
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
        }
    });
</script>

<div class="container-fluid px-4">
    <h1>Create Member</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('panel.members.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label for="member_types">Member Types</label>
            <select class="form-control" id="member_types" name="member_types[]" multiple>
                @foreach ($memberTypes as $memberType)
                    <option value="{{ $memberType->id }}">{{ $memberType->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label for="password">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="off">
        </div>

        <button type="submit" class="btn btn-primary">Create Member</button>
    </form>
</div>
@endsection
