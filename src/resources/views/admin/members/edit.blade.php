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
    <h1>Edit Profile</h1>

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

    <form action="{{ route('panel.members.update', ['member' => $member->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ $member->name }}">
</div>

<div class="mb-3">
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" name="email" value="{{ $member->email }}">
</div>

<div class="mb-3">
    <label for="member_types">Member Types</label>
    <select multiple class="form-control" id="member_types" name="member_types[]">
        @foreach ($memberTypes as $type)
            <option value="{{ $type->id }}" {{ in_array($type->id, $member->memberTypes->pluck('id')->toArray()) ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="status">Status</label>
    <select class="form-control" id="status" name="status">
        <option value="1" {{ $member->status == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ $member->status == 0 ? 'selected' : '' }}>Inactive</option>
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



<button type="submit" class="btn btn-primary">Update Profile</button>
</form>
</div>

@endsection

