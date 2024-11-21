<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Documentos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Importar Documentos</h1>

        <form action="/documents/import" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <div class="mb-3">
                <h3>Escolha um arquivo JSON:</h3>
                <input type="file" name="json_file" id="json_file" class="form-control" required>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Importar Documentos</button>
        </form>

        @isset($message)
        <div class="mt-5">
            <h3>Status:</h3>
            <pre class="bg-white p-3 border rounded">{{ $message }}</pre>
        </div>
        @endisset

        @isset($hasDocumentToProcess)
        <div class="mt-5">
            <form action="/documents/dispatch" method="GET" class="mt-4">
                @csrf
                <button type="submit" class="btn btn-primary">Processar Documentos</button>
            </form>
        </div>
        @endisset
    </div>
</body>

</html>