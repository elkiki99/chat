<aside class="p-1 bg-white border-gray-300 min-w-96 border-x">
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
                                <x-chat-image :chat="$chat" class="size-12" />
                            @else
                                @php
                                    $user = $chat->users->where('id', '!=', Auth::id())->first();
                                @endphp

                                <x-profile-picture :user="$user" class="size-12" />
                            @endif

                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <x-chat-name :chat="$chat" :user="$user" />

                                    <p class="text-xs text-gray-500">
                                        {{ $chat->messages->last()->created_at->format('H:i') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-1 mt-1">
                                    <x-chat-check :message="$chat->messages->last()" />

                                    <p class="text-sm text-gray-500">{{ Str::limit($chat->messages->last()->body, 25) }}</p>
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