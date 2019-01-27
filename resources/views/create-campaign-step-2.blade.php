@extends('layouts.dm')
@section('content')

    <div class="main">
        <section class="create-campaign">
            <div class="create-campaign__inner">

                <div class="create-campaign__inner-photo">
                    <img src="{{ asset('images/robot3.svg') }}" alt="robot">
                </div>
                <form action="{{route('uploadStepTwo')}}" class="upload-block__inner" method="POST"
                      enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="create-campaign__content create-campaign__content--nowrap">
                        <div class="create-campaign__inner-column first-column">
                            <div class="create-campaign__inner-header">
                                <button type="submit" name="back_step_2" value="true"
                                        class="btn btn--bold btn--arrow-left">Back
                                    <span class="arrow"></span></button>
                                <b class="create-campaign__step">
                                    Step
                                    <span class="create-campaign__step-current">2</span>
                                    <span class="create-campaign__step-next">5</span>
                                </b>
                                <h1>Load photo or video preview</h1>
                            </div>

                            <p class="create-campaign__description">
                                It is desirable to upload images not less than (954 x 694 px) or you can give
                                dreamachine image
                            </p>

                            <files-preview-mini route="{{route('deleteStepTwo', $campaign)}}"
                                                :preload="[
                                                @if(isset($media) && $media->isNotEmpty())
                                                    @foreach($media as $image)
                                                        { src:'{{$image->getUrl()}}', id:'{{$image->id}}'},
                                                    @endforeach
                                                @endif
                                                ]"
                            ></files-preview-mini>

                            <button type="submit" name="next_step_2" value="true"
                                    class="btn btn--big reverse btn--arrow-right btn--step">
                                Next step
                                <span class="arrow"></span>
                            </button>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="create-campaign__inner-column second-column">
                            <div class="upload-block">
                                <file-upload-main route-image="{{route('deleteStepTwo', ['campaign' => $campaign, 'type' => 'main_images'])}}" route-video="{{route('deleteStepTwo', ['campaign' => $campaign, 'type' => 'main_videos'])}}" route-upload-video="{{route('uploadVideo')}}"
                                                  preload-image="@if(isset($media_main) && $media_main->isNotEmpty()) {{$media_main[0]->getUrl()}} @endif"
                                                  preload-video="@if(isset($video) && $video->isNotEmpty()) {{'true'}} @endif"
                                                  preload-video-preview="@if(isset($video_preview) && $video_preview->isNotEmpty()) {{$video_preview[0]->getUrl()}}@endif"
                                                  @if(isset($video) && $video->isNotEmpty())
                                                        preload-video-name="{{$video[0]->file_name}}"
                                                  @endif

                                :max-size-image="10000"
                                :max-size-video="50000"
                                >
                                </file-upload-main>
                                <div class="upload-block__buttons">
                                    <div class="upload-block__buttons-button">
                                        <file-upload-mini name="file" :max-image="10" :max-size-image="10000"></file-upload-mini>
                                    </div>
                                    <div class="error-block error-block--upload" :class="{'active' : activeErrorUpload}">
                                        file size too large
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </section>
    </div>
@endsection