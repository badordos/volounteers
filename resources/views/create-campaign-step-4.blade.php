@extends('layouts.dm')
@section('content')

    <div class="main">
        <section class="create-campaign">
            <div class="create-campaign__inner">

                <div class="create-campaign__inner-photo">
                    <img src="{{ asset('images/robot3.svg') }}" alt="robot">
                </div>

                <form action="{{route('store-campaign-step-4')}}" method="post" class="editor__inner-form" @submit="validateCreateStep4">
                    {{csrf_field()}}
                    <div class="create-campaign__content create-campaign__content--editor">
                        <div class="create-campaign__inner-header">
                            <button type="submit" name="back_step_4" value="true" class="btn btn--bold btn--arrow-left">Back <span class="arrow"></span></button>
                            <b class="create-campaign__step">
                                Step
                                <span class="create-campaign__step-current">4</span>
                                <span class="create-campaign__step-next">5</span>
                            </b>
                            <h1>About campaign</h1>
                            @if ($errors->any())
                                <div class="alert alert-danger" style="margin-bottom: 10px;">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <editor upload-data='{{$data = $campaign->about_desc !== null ? $campaign->about_desc : ''}}' :max-length="20000000"></editor>

                        <transition name="fade">
                            <div class="loading" v-if="activeSendDataEditor">
                                Loading
                            </div>
                        </transition>


                        <button type="submit" name="next_step_4" value="true" class="btn btn--big reverse btn--arrow-right btn--step">
                            Next step
                            <span class="arrow"></span>
                        </button>

                        <p class="error-block error-block--editor" :class="{'active' : activeErrorEditor}">
                            File size too large
                        </p>

                    </div>
                </form>

            </div>
        </section>
    </div>
@endsection