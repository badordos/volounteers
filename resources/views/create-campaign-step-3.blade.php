@extends('layouts.dm')
@section('content')

    <div class="main">
        <section class="create-campaign">
            <div class="create-campaign__inner">

                <div class="create-campaign__inner-photo">
                    <img src="{{ asset('images/robot3.svg') }}" alt="robot">
                </div>
                <form action="{{route('store-campaign-step-3')}}" method="POST" class="create-step__inner-form">
                    {{csrf_field()}}
                    <div class="create-campaign__content create-campaign__content--position">
                        <div class="create-campaign__inner-header">
                            <button type="submit" class="btn btn--bold btn--arrow-left" name="back_step_3" value="true" data-back="true" @click="validateCreateStep3">Back<span class="arrow"></span></button>
                            <b class="create-campaign__step">
                                Step
                                <span class="create-campaign__step-current">3</span>
                                <span class="create-campaign__step-next">5</span>
                            </b>
                            <h1>Campaign steps</h1>
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

                        <create-steps
                                @if (isset($oldData))
                                    :upload-data="{{$oldData}}"
                                @endif
                                >
                            <create-step></create-step>
                        </create-steps>

                        <button type="submit" name="next_step_3" value="true" class="btn btn--big reverse btn--arrow-right btn--step" @click="validateCreateStep3">
                            Next step
                            <span class="arrow"></span>
                        </button>

                        <p class="error-block" :class="{'active' : activeErrorBlock}">
                            Check the entered data
                        </p>

                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection