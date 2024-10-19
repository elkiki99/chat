<div wire:poll.30s="updateUserStatus">
    @if ($isTyping && $typingUser && $typingUser->id !== Auth::id())
        <p class="text-gray-500">{{ $typingUser->name }} is typing...</p>
    @elseif($member->last_seen === null)
        <div class="flex items-center gap-2">
            <p class="text-gray-500">Online</p>
            <span class="relative flex size-2">
                <span
                    class="absolute inline-flex w-full h-full bg-green-400 rounded-full opacity-75 animate-ping"></span>
                <span class="relative inline-flex bg-green-500 rounded-full size-2"></span>
            </span>
        </div>
    @else
        <p class="text-gray-500">{{ \Carbon\Carbon::parse($member->last_seen)->diffForHumans() }}</p>
    @endif
</div>
