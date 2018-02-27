{{-- Editing the question. --}}
<div class="card card-default" v-if="editing">
    <div class="card-header">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <button class="btn btn-xs level-item" @click="editing = true" v-show="! editing">Edit</button>
            <button class="btn btn-primary btn-xs level-item" @click="update">Update</button>
            <button class="btn btn-xs level-item" @click="resetForm">Cancel</button>

            @can ('update', $post)
                <form action="{{ $post->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete Post</button>
                </form>
            @endcan

        </div>
    </div>
</div>


{{-- Viewing the question. --}}
<div class="card panel-default mb-5" v-else>

    <div class="card-body">
        <div class="mb-4">
            <h1 class="card-title" v-text="title"></h1>
            <h6 class="card-subtitle text-muted">
                Posted By <a href="#">{{ $post->author->name }}</a>
            </h6>
            <p class="card-text"><small class="text-muted">{{ $post->created_at->diffForHumans() }}</small></p>
        </div>
        
        <div class="card-text" v-html="body"></div>
    </div>

    <div class="card-footer" v-if="authorize('owns', post)">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
</div>