@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <post-view :post="{{ $post }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8 m-auto" v-cloak>
                    @include ('posts._question')

                    <h4>Comments</h4>
                    <hr>

                    <comments @added="commentsCount++" @removed="commentsCount--"></comments>
                </div>
            </div>
        </div>
    </post-view>
@endsection
