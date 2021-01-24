<li>
    <div class="comment">
        <span class="avatar">{{ nameCode($comment->name) }}</span>
        <div>
            <h6>{{ $comment->name }}</h6>
            <p>{{ $comment->comment }}</p>
            <span><i class="bi bi-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
            @if($comment->parent_id == 0 && auth()->check())
            <span onclick="__replyTo({{ $comment->id }}, '{{ $comment->name }}')"><i class="bi bi-reply ms-3"></i> Reply</span>
            @endif
        </div>
    </div>
    @if(count($comment->children) > 0)
        <ul>
            @each('site.partials.comment', $comment->children, 'comment')
        </ul>
    @endif
</li>
