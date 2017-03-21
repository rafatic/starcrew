@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="panel panel-default">
            <div class="panel-heading">Manage your profile</div>

            <div class="panel-body">
                <form action="updateProfile" method="post">
                    {{ csrf_field() }}
                    <h3>General informations</h3>

                    <label >Name : </label>{{Auth::user()->name }}
                    <br>
                    <div class="form-group">
                        <label for="email">Email : </label>
                        <input type="text" name="email" value="{{ Auth::user()->email }}">
                    </div>


                    <hr />

                    <h3>Spoken languages</h3>

                    <div class="form-group">
                        <label for="languageSelect">Select the language(s) you are confortable with :</label>
                        <br>
                        @foreach ($languages as $language)
                            <input type="checkbox" name="language[]" value="{{ $language->id }}"

                            @foreach ($userLanguages as $userLanguage)
                                @if ($userLanguage->language_id == $language->id)
                                    checked
                                @endif
                            @endforeach
                            > {{ $language->name }}<br>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <input type="submit" name="update" value="Save modifications" class="btn btn-primary">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


@endsection
