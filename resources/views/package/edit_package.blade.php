@extends('layouts.master')

@section('title', 'Default')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Packages</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Packages</li>
    <li class="breadcrumb-item active">modification package</li>
@endsection

@section('content')
 <div class="container-fluid">
    <div class="edit-profile">
        <div class="row">
            <div class="col-xl-12">
            @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                <form methode='post' action="/update-package-saving/{{$packages[0]->id_pack}}" enctype="multipart/form-data" class="card">
                    {{ csrf_field()}}
                    @method('put')
                    <div class="card-header">
                        <h4 class="card-title mb-0">modification package</h4>
                        <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- <div class="col-12 px-3">
                                <form class="dropzone dropzone-info" id="fileTypeValidation" action="/upload.php">
                                    <div class="dz-message needsclick">
                                           <i class="icon-cloud-up"></i>
                                           <h6>Drop files here or click to upload.</h6>
                                        <span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                                     </div>
                                </form>
                            </div> -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="col-form-label">Nom package</label>
                                    <input class="form-control" name="nom" value="{{$packages[0]->nom }}" type="text" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="col-form-label">Nom court</label>
                                    <input class="form-control" name="nom_court" value="{{$packages[0]->nom_court }}" type="text" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="col-form-label">Description</label>
                                    <input class="form-control" name="description" value="{{$packages[0]->description }}" type="text" >
                                </div>
                            </div>
                            <!-- <div class="mb-4">
                                <label>Logo du pack: </label>
                                <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload" name="logo_pack"/>
                            </div> -->
                            <div class="col">
                                <div class="m-t-15 m-checkbox-inline">
                                    @foreach($garanties as $gar)
                                        <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                            <input class="form-check-input" name="garanties[]" value="{{$gar->id_garantie}}" id="{{$gar->id_garantie}}" type="checkbox"   >
                                            <label class="form-check-label" for="{{$gar->id_garantie}}">{{$gar->nom_garantie}}</label>
                                        </div>
                                    @endforeach
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                        <button class="btn btn-primary" type="submit">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
    // Assuming $selectedGaranties contains the selected IDs
    var selectedGaranties = <?php echo json_encode($distinctGaranties); ?>;

    // Loop through each selected ID and check the corresponding checkbox
    selectedGaranties.forEach(function(garantieId) {
        // Use the ID to construct the checkbox selector
        var checkboxSelector = 'input[type="checkbox"][value="' + garantieId + '"]';

        // Check the checkbox
        $(checkboxSelector).prop('checked', true);
    });
});
</script>
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
@endsection