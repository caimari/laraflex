@extends('laraflex::layouts.admin')

@section('content')

<div class="container">

@if(Route::has('crud.' . str_replace('_', '-', $modelName) . '.create'))
    <a href="{{ route('crud.' . str_replace('_', '-', $modelName) . '.create') }}" class="btn btn-success">Create</a>
@endif


    <table class="table">
        <thead>
            <tr>
                {{-- Column headers --}}
                <th>id</th>
<th>uuid</th>
<th>connection</th>
<th>queue</th>
<th>payload</th>
<th>exception</th>
<th>failed_at</th>

                <th>Actions</th> {{-- Nueva columna de acciones --}}
            </tr>
        </thead>
        <tbody>
            {{-- Rows --}}
            @foreach ($items as $item)
                <tr>
                    <td>{{$item->id}}</td>
<td>{{$item->uuid}}</td>
<td>{{$item->connection}}</td>
<td>{{$item->queue}}</td>
<td>{{$item->payload}}</td>
<td>{{$item->exception}}</td>
<td>{{$item->failed_at}}</td>

                    <td>
@php
    $canEdit = Route::has('crud.' . $modelName . '.edit');
    $canDelete = Route::has('crud.' . $modelName . '.destroy');
@endphp

@if($canEdit)
    <a href="{{ route('crud.' . $modelName . '.edit', $item->id) }}" class="btn btn-primary">Edit</a>
@endif




@php
    $routeExists = Route::has("crud.failed-jobs.destroy");
@endphp

@if($routeExists)
    <form action="{{ route("crud.failed-jobs.destroy", $item->id) }}" method="POST" style="display:inline;">
        @method('DELETE')
        @csrf
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
