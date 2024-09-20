<p class="text-sm font-medium">
    @if ($chat->is_group)
        {{ $chat->name }}
    @else
        {{ $user->name }}
    @endif
</p>