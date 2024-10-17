<div>
    @if ($isTyping && $typingUser && $typingUser->id !== Auth::id())
        <p class="text-gray-500">{{ $typingUser->name }} is typing...</p>
    @else
        @php
            $chat = App\Models\Chat::find($chatId);
            $otherUser = $chat->users()
                ->where('chat_user.user_id', '!=', Auth::id())
                ->first();
        @endphp
        
        @if ($otherUser)
            @if (Cache::has('user-is-online-.' . $otherUser->id))
                <p class="text-gray-500">Online</p>
            @else
                <p class="text-gray-500">Last seen {{ $otherUser->last_seen }}</p>
            @endif
        @endif
    @endif
</div>