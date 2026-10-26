<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File Management - qadrlabs.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card rounded">
                    <div class="card-body">
                        <a href="{{ route('files.create') }}" class="btn btn-md btn-primary mb-3 float-end">Upload File</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Nama File</th>
                                    <th scope="col" style="width: 20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($files as $file)
                                    <tr>
                                        <td>{{ $file->original_name }}</td>
                                        <td>{{ $file }}</td>
                                        <td class="text-center">
                                      
                                        <a href="{{ route('files.download',  ($file)) }}"  class="btn btn-sm btn-primary">Download</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted text-center">Data file belum tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $files->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>