<div wire:poll.5s="updateUserStatus"> <!-- Polling cada 5 segundos -->
    @if ($isTyping && $typingUser && $typingUser->id !== Auth::id())
        <p class="text-gray-500">{{ $typingUser->name }} is typing...</p>
    @elseif($member->last_seen === null)
        <p class="text-gray-500">Online</p>
    @else
        <p class="text-gray-500">Offline</p>
    @endif
</div>