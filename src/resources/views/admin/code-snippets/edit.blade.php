@extends('laraflex::layouts.admin')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/codemirror.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/mode/php/php.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/theme/dracula.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.2/mode/htmlmixed/htmlmixed.min.js"></script>

<style>
    .CodeMirror {
        height: auto;
        min-height: 500px;
    }
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-code"></i> Edit Code Snippet</h1>
    <ol class="breadcrumb mb-4"></ol>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" id="code-snippet-tab" data-bs-toggle="tab" href="#code-snippet-content" role="tab" aria-controls="code-snippet-content" aria-selected="true">Code Snippet</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="versions-tab" data-bs-toggle="tab" href="#versions-content" role="tab" aria-controls="versions-content" aria-selected="false">Versions</a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <div class="tab-pane fade show active" id="code-snippet-content" role="tabpanel" aria-labelledby="code-snippet-tab">
            <form action="{{ route('panel.code-snippets.update', $codeSnippet->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $codeSnippet->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="2">{{ $codeSnippet->description }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="code-snippet-editor" name="content" rows="20">{{ $codeSnippet->content }}</textarea>
                </div>

                <div class="mb-3 col-6">
                    <label for="version_name" class="form-label">Version Name</label>
                    <input type="text" class="form-control" id="version_name" name="version_name" value="">
                </div>


                <button type="submit" class="btn btn-primary">Update</button>
                <button id="set-home-page" class="btn{{ $isHomePage ? ' btn-primary' : ' btn-secondary' }}" {{ $isHomePage ? 'disabled' : '' }}>
                @if ($isHomePage)
                    This is the home page
                @else
                    Set as home page
                @endif
                </button>

                <div class="margin"></div>


            </form>
        </div>

        <div class="tab-pane fade" id="versions-content" role="tabpanel" aria-labelledby="versions-tab">
            <h3>Previous versions</h3>
            <p></p>
            @foreach ($versions as $version)
                <div>
                    <p>Version: {{ $version->name }} (from: {{ $version->created_at }})</p>
                    <form action="{{ route('panel.code-snippets.revert', ['codeSnippet' => $codeSnippet->id, 'version' => $version->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning">Revert to this version</button>
                    </form>
                    <p></p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="margin"></div>

<script>
    var codeSnippetEditor = CodeMirror.fromTextArea(document.getElementById("code-snippet-editor"), {
        lineNumbers: true,
        mode: "htmlmixed",
        theme: "dracula",
        viewportMargin: Infinity
    });
</script>


<script>
$(document).ready(function() {
    $('#set-home-page').click(function(event) {
        event.preventDefault(); // Añade esta línea si el botón está dentro de un <form>

        var codeSnippetId = {{ $codeSnippet->id }};

        $.ajax({
            type: 'POST',
            url: '{{ route('setHomeSnippet', '') }}/' + codeSnippetId,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                // Mostrar el mensaje de éxito solo una vez
                alert("The code snippet has been set as the front page");

                // Cambiar el estado del botón
                $('#set-home-page').prop('disabled', true).removeClass('btn-secondary').addClass('btn-primary').text('This is the front page');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseText;
                alert("Error: " + errorMessage);
            }
        });
    });
});
</script>

@endsection
