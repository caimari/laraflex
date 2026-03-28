@extends('laraflex::layouts.admin')

@section('content')

<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('#members-type-table').DataTable();
    });
</script>

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="far fa-id-badge"></i> Member Type</h1>
    <ol class="breadcrumb mb-4"></ol>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('panel.members.type.create') }}" class="btn btn-primary mb-3">New Type</a>

    <table id="members-type-table" class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($memberTypes as $memberType)
            <tr>
                <td>{{ $memberType->name }}</td>
                <td>{{ $memberType->status ? 'Active' : 'Inactive' }}</td>
                <td>
    <a href="{{ route('panel.members.type.edit', ['type' => $memberType->id]) }}" class="btn btn-primary">Edit</a>
    <form action="{{ route('panel.members.type.destroy', ['type' => $memberType->id]) }}" method="POST" style="display: inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</td>

            </tr>
        @endforeach
    </tbody>
</table>



</div>

@endsection




