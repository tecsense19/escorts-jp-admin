@php
    $id = isset($datas) ? $datas->Id : '';
    $title = isset($datas) ? $datas->title : '';
    $description = isset($datas) ? $datas->description : '';
@endphp

@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Escorts</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Terms Condition</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    @include('flash-message')
    <section class="section profile">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <div class="tab-content pt-2">
                            <!-- Profile Edit Form -->
                            <form class="row g-3" method="POST" action="{{ route('admin.save.termcondition') }}" id="profileForm" enctype='multipart/form-data'>
                                {!! csrf_field() !!}
                                <div class="row mb-3">
                                    <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Title</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="title" type="text" class="form-control" id="title" value="{{$title}}" required>
                                        <input type="hidden" name="id" id="id" value="{{ $id }}"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="about" class="col-md-4 col-lg-3 col-form-label">Description</label>
                                    <div class="col-md-8 col-lg-9">
                                        <textarea name="description" class="form-control editor" id="about" style="height: 100px">{{$description}}</textarea>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">{{ $id ? 'Update' : 'Create' }}</button>
                                </div>
                            </form>
                            <!-- End Profile Edit Form -->
                        </div>
                        <!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@include('admin.layout.end')

<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
            .create( document.querySelector( '.editor' ) )
            .then( editor => {
                    console.log( editor );
            } )
            .catch( error => {
                    console.error( error );
            } );
</script>