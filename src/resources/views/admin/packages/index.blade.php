@extends('laraflex::layouts.admin')

@section('content')

@auth
    <div class="container-fluid px-4">
    <h1 class="mt-4"><i class="far fa-file-alt"></i> Packages</h1>
    <ol class="breadcrumb mb-4">
    </ol>


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


    <form id="package-composer-install">
    @csrf
    <input type="text" name="package_name" placeholder="Package name">
    <button type="submit">Install</button>
</form>

<div id="installation-result"></div>

<script>
    document.getElementById('package-composer-install').addEventListener('submit', function(event) {
        event.preventDefault();

        var form = event.target;
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('package.composer.install') }}", true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var result = JSON.parse(xhr.responseText);
                document.getElementById('installation-result').innerHTML = result.result;
            }
        };
        xhr.send(formData);
    });
</script>


</div>




@endauth
@endsection
