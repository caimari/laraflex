@extends('laraflex::layouts.admin')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-code"></i> Code Snippets</h1>
    <ol class="breadcrumb mb-4"></ol>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('panel.code-snippets.create') }}" class="btn btn-primary mb-3">Create New Code Snippet</a>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($codeSnippets as $codeSnippet)
                <tr>
                    <td>{{ $codeSnippet->name}}</td>
                    <td>{{ $codeSnippet->description }}</td>
                    <td>
                        <a href="{{ route('panel.code-snippets.edit', $codeSnippet->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('panel.code-snippets.destroy', $codeSnippet->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Code Snippet?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>



@endsection

