<li>
    <div class="comment">
        <span class="avatar">{{ $comment->name_code }}</span>
        <div>
            <h6>{{ $comment->name }}</h6>
            <p>{{ $comment->comment }}</p>
            <span><i class="bi bi-clock"></i> {{ $comment->createdAtHumanReadable }}</span>
            @if($comment->parent_id == 0 && auth()->check())
            <span onclick="__replyTo({{ $comment->id }}, '{{ $comment->name }}')"><i class="bi bi-reply ms-3"></i> Reply</span>
            @endif
        </div>
        @if($comment->user_id == auth()->id())
            <span class="comment-dots">
                <i class="bi bi-three-dots-vertical"></i>
            </span>
        @endif
    </div>
    @if(count($comment->children) > 0)
        <ul>
            @each('site.partials.comment', $comment->children, 'comment')
        </ul>
    @endif
</li>
