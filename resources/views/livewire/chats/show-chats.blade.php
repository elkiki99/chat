<aside class="p-1 bg-white border-gray-300 min-w-96 border-x">
    <h2 class="px-4 py-2 text-xl font-semibold">Chats</h2>

    <div class="">
        <ul>
            @forelse ($chats as $chat)
                @php
                    $lastMessage = $chat->messages->last();
                    $isCurrentUser = $lastMessage && $lastMessage->user_id === Auth::id();
                    $user = $chat->users->where('id', '!=', Auth::id())->first();
                    $unreadMessages = $chat
                        ->messages()
                        ->where('user_id', '!=', Auth::id())
                        ->whereDoesntHave('seenBy', function ($query) {
                            $query->where('user_id', Auth::id());
                        })
                        ->count();
                @endphp

                <li>
                    <a wire:click="selectChat({{ $chat->id }})"
                        class="block p-3 rounded cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-2">
                            <!-- Chat image -->
                            @if ($chat->is_group)
                                <x-chat-image :chat="$chat" class="size-12" />
                            @else
                                <x-profile-picture :user="$user" class="size-12" />
                            @endif

                            <div class="flex-1 mx-2">
                                <div class="flex items-center justify-between">
                                    <!-- Chat name -->
                                    <p class="text-sm font-medium">
                                        @if ($chat->is_group)
                                            {{ $chat->name }}
                                        @else
                                            {{ $user->name }}
                                        @endif
                                    </p>

                                    <!-- Last message -->
                                    <p class="text-xs text-gray-500">
                                        {{ $lastMessage->created_at->format('H:i') }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">

                                    <div class="flex items-center gap-1 mt-1">
                                        @if ($isCurrentUser)
                                            <x-chat-check :message="$lastMessage" />
                                        @endif

                                        @if ($chat->is_group && !$isCurrentUser)
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm text-gray-600">
                                                    {{ Str::limit($lastMessage->user->name, 12, '') }}: </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ Str::limit($lastMessage->body, 25) }}
                                                </p>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500">{{ Str::limit($lastMessage->body, 25) }}</p>
                                        @endif
                                    </div>

                                    @if (!$isCurrentUser && $unreadMessages)
                                        <div
                                            class="flex items-center justify-center text-sm text-white bg-green-500 rounded-full size-4">
                                            <p>{{ $unreadMessages }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li class="p-4">No chats found</li>
            @endforelse
        </ul>
    </div>
</aside>
