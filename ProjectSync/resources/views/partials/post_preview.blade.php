<li class="post" data-id="{{$post->id}}">
    <a href="{{ route('post.show', ['project_id' => $post->project_id,'id' => $post->id]) }}" class="post">
        <h3>{{ $post->title }}</h3>
        <p>Author {{ $post->author->username }}</p>
        <p>Description: {{ \Illuminate\Support\Str::limit($post->description, $limit = 50, $end = '...') }}</p>
        <p>Upvotes {{ $post->upvotes }}</p>
    </a>
</li>