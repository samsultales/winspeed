 
@extends ('admin.app')
 

 @section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
               
                <a href="{{ route('files.index') }}" class="btn btn-md btn-link mb-3">Back</a>

                <div class="card rounded">
                    <div class="card-body">

                        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">File</label>
                                <input type="file" class="form-control @error('file') is-invalid @enderror" name="file">

                                <!-- error message untuk image -->
                                @error('file')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary me-3">Upload</button>

                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection