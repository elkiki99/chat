<aside class="p-1 bg-white border-gray-300 min-w-96 border-x">
    <h2 class="px-4 py-4 text-xl font-semibold">Chats</h2>

    <!-- Search bar for chats -->
    <div class="px-4">
        <div class="relative flex items-center">
            <span class="absolute left-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-gray-500 size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </span>
            
            <input wire:model.live='search' class="w-full pl-10 text-sm border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600" placeholder="Search a chat...">
        </div>
    </div>

    <!-- Chats list -->
    <div class="mt-4">
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
                <li class="p-4 text-gray-500">No chats found</li>
            @endforelse
        </ul>
    </div>
</aside>
