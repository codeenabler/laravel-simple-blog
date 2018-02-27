@forelse ($posts as $post)
    <div class="card mb-3">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $post->path() }}">
                            {{ $post->title }}
                        </a>
                    </h4>

                    <h6>
                        Posted By: <a href="#">{{ $post->author->name }}</a>
                    </h6>
                </div>

                <a href="{{ $post->path() }}">
                    {{ $post->comments_count }} {{ str_plural('comment', $post->comments_count) }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="body">{!! $post->body !!}</div>
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse