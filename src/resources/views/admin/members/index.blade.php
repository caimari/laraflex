@extends('laraflex::layouts.admin')

@section('content')

<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('#members-table').DataTable();
    });
</script>

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-users"></i> Members</h1>
    <ol class="breadcrumb mb-4"></ol>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('panel.members.create') }}" class="btn btn-primary mb-3">New Member</a>

    <table id="members-table" class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
            <tr>
                <td>{{ $member->name }}</td>
                <td>{{ $member->email }}</td>
                <td>
                    @foreach ($member->memberTypes as $type)
                        {{ $type->name }},
                    @endforeach
                </td>
                <td>{{ $member->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('panel.members.edit', ['member' => $member->id]) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('panel.members.destroy', ['member' => $member->id]) }}" method="POST" style="display: inline-block">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>



</div>

@endsection




