@extends('layouts.authorization')
@section('content')

    <div class="main">
        <div class="main__inner">

            <section class="authorization">
                <div class="authorization__inner">

                    <h1>Reset password</h1>

                    <form action="#!">
                        <label class="label">
                            <h3>E-mail</h3>
                            <input type="email" required placeholder="Email">
                        </label>

                        <input type="submit" value="Reset" class="btn">
                    </form>

                </div>
            </section>

        </div>
    </div>

@endsection