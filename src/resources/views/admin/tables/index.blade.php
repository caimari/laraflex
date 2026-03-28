@extends('laraflex::layouts.admin')
@section('content')

<style>
    .crud-table {
        background-color: #9a9dc0; /* Color de fondo personalizado para las tablas con CRUD */
        color: #ffffff; /* Color de texto personalizado para las tablas con CRUD */
        /* Agrega otros estilos personalizados según tus necesidades */
    }
</style>


@if(session('success'))
<div class="alert alert-success" role="alert">
    {!! session('success') !!}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger" role="alert">
    {!! session('error') !!}
</div>
@endif

<div class="container-fluid px-4">
    <div class="accordion" id="accordionExample">
        @foreach ($tables as $tableName => $columns)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $tableName }}">
            <button class="accordion-button collapsed {{ in_array($tableName, $tablesWithCrud) ? 'crud-table' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $tableName }}" aria-expanded="false" aria-controls="collapse{{ $tableName }}">
    {{ $tableName }}
</button>

            </h2>

            <div id="collapse{{ $tableName }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $tableName }}" data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    <form method="POST" action="">
                        @csrf
                        <input type="hidden" name="table" value="{{ $tableName }}">

                        <table id="datatablesSimple" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Length</th>
                                    <th>Null</th>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll{{ $tableName }}">
                                            <label class="form-check-label" for="checkAll{{ $tableName }}">
                                                Select All
                                            </label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($columns as $column)
                                <tr>
                                    <td>{{ $column->getName() }}</td>
                                    <td>{{ $column->getType()->getName() }}</td>
                                    <td>{{ $column->getLength() }}</td>
                                    <td>{{ $column->getNotnull() ? 'No' : 'Yes' }}</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input column-checkbox" type="checkbox" value="{{ $column->getName() }}" id="{{ $tableName . '-' . $column->getName() }}" name="columns[{{ $tableName }}][]">
                                            <label class="form-check-label" for="{{ $tableName . '-' . $column->getName() }}">
                                                Select
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="form-control column-type" name="columnTypes[{{ $tableName }}][{{ $column->getName() }}]">
                                        <option value="text">Text</option>
                                            <option value="password">Password</option>
                                            <option value="textarea">Textarea</option>
                                            <option value="ckeditor">Ckeditor</option>
                                            <option value="select">Select</option>
                                            <option value="radio">Radio Buttons</option>
                                            <option value="checkbox">Checkbox</option>
                                            <option value="number">Number</option>
                                            <option value="email">Email</option>
                                            <option value="date">Date</option>
                                            <option value="time">Time</option>
                                            <option value="file">File</option>
                                            <!-- Agrega más opciones de tipo de columna aquí -->
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style="display: flex; gap: 10px;">
                            <button formaction="{{ route('admin.generateIndex') }}" type="submit" class="btn btn-primary mt-3">Generate Index</button>
                            <button formaction="{{ route('admin.generateCreate') }}" type="submit" class="btn btn-primary mt-3">Generate Create View</button>
                            <button formaction="{{ route('admin.generateEdit') }}" type="submit" class="btn btn-primary mt-3">Generate Edit View</button>
                            <a href="{{ route('admin.deleteCrud', ['table' => $tableName]) }}" class="btn btn-danger mt-3">Delete CRUD</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('click', function (event) {
            if (event.target.id.startsWith('checkAll')) {
                var table = event.target.id.substring(8);
                document.querySelectorAll('#collapse' + table + ' .column-checkbox').forEach(function (columnCheckbox) {
                    columnCheckbox.checked = event.target.checked;
                });
            }
        });
    });
</script>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#datatablesSimple').DataTable();
    });
</script>
@endpush

@endsection
