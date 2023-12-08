<li class="post" data-id="{{$post->id}}">
    <div class="post_content_preview">
        <div class="upvote-downvote">
            <form method="POST" class="upvote" action="{{ route('post.upvote', ['id' => $post->id]) }}">
                @csrf
                <input type="hidden" name="upvote" class="post-form" value="{{ 'up' }}">
                <button type="submit" class="upvote-button" data-action="up">&#9650;</button>
            </form>
            <span class="upvotes-count">{{ $post->upvotes }}</span>
            <form method="POST" class="downvote" action="{{ route('post.upvote', ['id' => $post->id]) }}">
                @csrf
                <input type="hidden" name="upvote" class="post-form" value="{{ 'down' }}">
                <button type="submit" class="downvote-button" data-action="down">&#9660;</button>
            </form>
        </div>

        <div class="preview">
            <a href="{{ route('post.show', ['project_id' => $post->project_id,'id' => $post->id]) }}" class="post">
                <h3>{{ $post->title }}</h3>
                <p>Author {{ $post->author->username }}</p>
                <p>Description: {{ \Illuminate\Support\Str::limit($post->description, $limit = 50, $end = '...') }}</p>
            </a>
        </div>
    </div>
</li>