@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 m-auto">
                <form method="GET" action="/posts/search">
                    <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search for something.." name="q" value="{{ request('q') }}">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit">Search</button>
                        </div>
                    </div>
                </form>

                @include ('posts._list')

                {{ $posts->render() }}
            </div>
        </div>
    </div>
@endsection
