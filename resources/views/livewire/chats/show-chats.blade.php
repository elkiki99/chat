<aside class="w-1/4 p-1 bg-white border-gray-300 border-x">
    <h2 class="px-4 py-2 text-xl font-semibold">Chats</h2>

    <div class="">
        <ul>
            @forelse ($chats as $chat)
                <li>
                    <a wire:click="selectChat({{ $chat->id }})"
                        class="block p-3 rounded cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-2">
                            <!-- Chat image -->
                            @if ($chat->is_group)
                                <x-chat-image :chat="$chat" />
                            @else
                                @php
                                    $user = $chat->users->where('id', '!=', Auth::id())->first();
                                @endphp

                                <x-profile-picture :user="$user" />
                            @endif

                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium">
                                        @if ($chat->is_group)
                                            {{ $chat->name }}
                                        @else
                                            {{ $user->name }}
                                        @endif
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $chat->messages->last()->created_at->format('H:i') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-1 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>

                                    <p class="text-sm text-gray-500">{{ Str::limit($chat->messages->last()->body, 25) }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li>No chats found</li>
            @endforelse
        </ul>
    </div>
</aside>