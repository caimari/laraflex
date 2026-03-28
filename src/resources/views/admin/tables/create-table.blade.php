@extends('laraflex::layouts.admin')

@section('content')


<div class="container-fluid px-4">
<h1>Create menus</h1>


<form method="POST" action="{{ route('tables.store') }}">
    @csrf
    <div class="form-group">
        <label for="table_name">Table Name</label>
        <input type="text" class="form-control" id="table_name" name="table_name" required>
    </div>
    
    <div id="columns-container">
        <div class="column-group">
            <div class="d-flex align-items-center mb-2">
                <div class="form-group me-2">
                    <label for="column_name[]">Column Name</label>
                    <input type="text" class="form-control" name="column_name[]" required>
                </div>
                <div class="form-group me-2">
                    <label for="column_type[]">Column Type</label>
                    <select class="form-control" name="column_type[]">
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="select">Select</option>
                        <option value="date">Date</option>
                        <!-- Agrega más opciones de tipo de columna aquí -->
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-danger" onclick="removeColumn(this)">Remove</button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary" onclick="addColumn()">Agregar Columna</button>

    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    function addColumn() {
        var container = document.getElementById('columns-container');
        var columnGroup = document.createElement('div');
        columnGroup.classList.add('column-group');

        var columnInline = document.createElement('div');
        columnInline.classList.add('input-group', 'mb-2');

        var columnNameInput = document.createElement('input');
        columnNameInput.setAttribute('type', 'text');
        columnNameInput.classList.add('form-control');
        columnNameInput.setAttribute('name', 'column_name[]');
        columnNameInput.setAttribute('required', true);

        var columnTypeSelect = document.createElement('select');
        columnTypeSelect.classList.add('form-control');
        columnTypeSelect.setAttribute('name', 'column_type[]');

        var textOption = document.createElement('option');
        textOption.setAttribute('value', 'text');
        textOption.innerText = 'Text';

        var textareaOption = document.createElement('option');
        textareaOption.setAttribute('value', 'textarea');
        textareaOption.innerText = 'Textarea';

        var selectOption = document.createElement('option');
        selectOption.setAttribute('value', 'select');
        selectOption.innerText = 'Select';

        var dateOption = document.createElement('option');
        dateOption.setAttribute('value', 'date');
        dateOption.innerText = 'Date';

        // Agrega más opciones de tipo de columna aquí

        columnTypeSelect.appendChild(textOption);
        columnTypeSelect.appendChild(textareaOption);
        columnTypeSelect.appendChild(selectOption);
        columnTypeSelect.appendChild(dateOption);

        var removeButton = document.createElement('button');
        removeButton.setAttribute('type', 'button');
        removeButton.classList.add('btn', 'btn-danger', 'input-group-text');
        removeButton.innerText = 'Remove';
        removeButton.addEventListener('click', function() {
            removeColumn(columnGroup);
        });

        columnInline.appendChild(columnNameInput);
        columnInline.appendChild(columnTypeSelect);

        var inputGroupAppend = document.createElement('div');
        inputGroupAppend.classList.add('input-group-append');
        inputGroupAppend.appendChild(removeButton);
        columnInline.appendChild(inputGroupAppend);

        columnGroup.appendChild(columnInline);

        container.appendChild(columnGroup);
    }

    function removeColumn(columnGroup) {
        var container = document.getElementById('columns-container');
        container.removeChild(columnGroup);
    }
</script>

@endsection
