<div>
    @if ($isTyping && $typingUser && $typingUser->id !== Auth::id())
        <p class="text-gray-500">
            {{ $typingUser->name }} is typing...
        </p>
    @else
        <p class="text-gray-500">Online</p>
    @endif
</div>