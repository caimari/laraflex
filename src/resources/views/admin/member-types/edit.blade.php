@extends('laraflex::layouts.admin')
@section('content')

<div class="container-fluid px-4">
    <h1>Edit Member Type</h1>

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

    <form action="{{ route('panel.members.type.update', ['type' => $type->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $type->name) }}">
    </div>

    <div class="mb-3">
    <label for="status">Status</label>
    <select class="form-control" id="status" name="status">
        <option value="1" {{ $type->status == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ $type->status == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
</div>


    <button type="submit" class="btn btn-primary">Update Member Type</button>
</form>


</div>
@endsection

