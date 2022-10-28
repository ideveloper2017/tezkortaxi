@extends('cms.layout.base')

@section('title', 'Add  New')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">

            <h5 style="margin-bottom: 2em;"><span class="s-icon"><i class="ti-layout"></i></span>&nbsp;Add New Page</h5><hr>

            <form class="form-horizontal" action="{{route('cms.faq.store')}}" method="POST" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="col-md-10">
                    <ul class="nav nav-tabs m-b-0-5" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab" aria-expanded="false">En</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab" aria-expanded="true">Ar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile_1" role="tab" aria-expanded="true">Fr</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile_2" role="tab" aria-expanded="true">Ru</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile_3" role="tab" aria-expanded="true">Sp</a>
                        </li>
                        
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="home" role="tabpanel" aria-expanded="false">
                            <div class="form-group row">
                                <label for="name" class="col-xs-12 col-form-label">Page Name</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('en_title') }}" name="en_title" required id="en_title" placeholder="Title">
                                </div>
                            </div>
                                                        
                            <div class="form-group row">
                                <label for="meta_keys" class="col-xs-12 col-form-label">Meta Keys</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('en_meta_keys') }}" name="en_meta_keys" required id="meta_keys" placeholder="Meta Keys">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_description" class="col-xs-12 col-form-label">Meta Description</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('en_meta_description') }}" name="en_meta_description" required id="meta_description" placeholder="Meta Description">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="en_question" class="col-xs-12 col-form-label">Question</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('en_question') }}" name="en_question" required id="en_question" placeholder="Question">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="picture" class="col-xs-12 col-form-label">Page Image</label>
                                <div class="col-xs-10">
                                    <input type="file" accept="image/*" name="image" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
                                </div>
                            </div>

                     
                            <div class="form-group row">
                                <label for="description" class="col-xs-12 col-form-label">Description</label>
                                <div class="col-xs-10">
                                    <textarea class="form-control" id="myeditor"  type="number"  name="en_description" required  placeholder="Description" rows="4">{{ old('en_description') }}</textarea>
                                </div>
                            </div>

                            
                        </div>
                        <div class="tab-pane" id="profile" role="tabpanel" aria-expanded="true">
                             <div class="form-group row">
                                <label for="name" class="col-xs-12 col-form-label">Page Name</label>
                                <div class="col-xs-10">
                                    <input class="form-control arabic-content" type="text" value="{{ old('ar_title') }}" name="ar_title" required id="ar_title" placeholder="Title">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_keys" class="col-xs-12 col-form-label">Meta Keys</label>
                                <div class="col-xs-10">
                                    <input class="form-control arabic-content" type="text" value="{{ old('ar_meta_keys') }}" name="ar_meta_keys" required id="meta_keys" placeholder="Meta Keys">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_description" class="col-xs-12 col-form-label">Meta Description</label>
                                <div class="col-xs-10">
                                    <input class="form-control arabic-content" type="text" value="{{ old('ar_meta_description') }}" name="ar_meta_description" required id="meta_description" placeholder="Meta Description">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ar_question" class="col-xs-12 col-form-label">Question</label>
                                <div class="col-xs-10">
                                    <input class="form-control arabic-content" type="text" value="{{ old('ar_question') }}" name="ar_question" required id="ar_question" placeholder="Question">
                                </div>
                            </div>
                     
                            <div class="form-group row">
                                <label for="description" class="col-xs-12 col-form-label">Description</label>
                                <div class="col-xs-10">
                                    <textarea class="form-control arabic-content" id="ar_myeditor"  type="number"  name="ar_description" required  placeholder="Description" rows="4">{{ old('ar_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                       
                       <div class="tab-pane" id="profile_1" role="tabpanel" aria-expanded="true">
                             <div class="form-group row">
                                <label for="name" class="col-xs-12 col-form-label">Page Name</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('fr_title') }}" name="fr_title" required id="fr_title" placeholder="Title">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_keys" class="col-xs-12 col-form-label">Meta Keys</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('fr_meta_keys') }}" name="fr_meta_keys" required id="fr_meta_keys" placeholder="Meta Keys">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_description" class="col-xs-12 col-form-label">Meta Description</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('fr_meta_description') }}" name="fr_meta_description" required id="fr_meta_description" placeholder="Meta Description">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fr_question" class="col-xs-12 col-form-label">Question</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('fr_question') }}" name="fr_question" required id="fr_question" placeholder="Question">
                                </div>
                            </div>
                     
                            <div class="form-group row">
                                <label for="description" class="col-xs-12 col-form-label">Description</label>
                                <div class="col-xs-10">
                                    <textarea class="form-control" id="fr_description"  type="number"  name="fr_description" required  placeholder="Description" rows="4">{{ old('fr_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile_2" role="tabpanel" aria-expanded="true">
                             <div class="form-group row">
                                <label for="name" class="col-xs-12 col-form-label">Page Name</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('ru_title') }}" name="ru_title" required id="ru_title" placeholder="Title">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_keys" class="col-xs-12 col-form-label">Meta Keys</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('ru_meta_keys') }}" name="ru_meta_keys" required id="meta_keys" placeholder="Meta Keys">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_description" class="col-xs-12 col-form-label">Meta Description</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('ru_meta_description') }}" name="ru_meta_description" required id="meta_description" placeholder="Meta Description">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ru_question" class="col-xs-12 col-form-label">Question</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('ru_question') }}" name="ru_question" required id="ru_question" placeholder="Question">
                                </div>
                            </div>
                     
                            <div class="form-group row">
                                <label for="description" class="col-xs-12 col-form-label">Description</label>
                                <div class="col-xs-10">
                                    <textarea class="form-control" id="ar_myeditor"  type="number"  name="ru_description" required  placeholder="Description" rows="4">{{ old('ru_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile_3" role="tabpanel" aria-expanded="true">
                             <div class="form-group row">
                                <label for="name" class="col-xs-12 col-form-label">Page Name</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('sp_title') }}" name="sp_title" required id="sp_title" placeholder="Title">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_keys" class="col-xs-12 col-form-label">Meta Keys</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('sp_meta_keys') }}" name="sp_meta_keys" required id="meta_keys" placeholder="Meta Keys">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="meta_description" class="col-xs-12 col-form-label">Meta Description</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('sp_meta_description') }}" name="sp_meta_description" required id="meta_description" placeholder="Meta Description">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="sp_question" class="col-xs-12 col-form-label">Question</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="{{ old('sp_question') }}" name="sp_question" required id="sp_question" placeholder="Question">
                                </div>
                            </div>
                     
                            <div class="form-group row">
                                <label for="description" class="col-xs-12 col-form-label">Description</label>
                                <div class="col-xs-10">
                                    <textarea class="form-control" id="ru_myeditor"  type="number"  name="sp_description" required  placeholder="Description" rows="4">{{ old('sp_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                       
                        
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <button type="submit" class="btn btn-success shadow-box btn-block">Save</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 offset-md-6">
                                <a href="{{ route('cms.faq.index') }}" class="btn btn-danger shadow-box btn-block">Cancel</a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('myeditor');
</script>
@endsection

