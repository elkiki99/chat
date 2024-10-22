<aside x-cloak x-data="{ showAside: @entangle('showAside') }" x-bind:class="showAside ? 'w-full md:w-96' : 'hidden md:block'"
    class="h-screen p-1 ml-0 overflow-auto bg-white border-r border-gray-300 dark:bg-gray-800 dark:border-gray-900 min-w-96 sm:ml-12">
    <h2 class="px-4 py-4 text-xl font-semibold dark:text-gray-200">Chats</h2>

    <!-- Search bar for chats -->
    <div class="flex items-center justify-between">
        <div class="w-full px-4">
            <div class="relative flex items-center">
                <span class="absolute left-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="text-gray-500 size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </span>

                <input wire:model.live='search'
                    class="w-full pl-10 text-sm border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600"
                    placeholder="Search a chat...">
            </div>
        </div>

        <!-- Dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="p-2 mr-3 dark:hover:bg-gray-750 hover:bg-gray-100 hover:rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="text-gray-700 dark:text-gray-200 size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-chat')">New chat</x-dropdown-link>
                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-group')">New group</x-dropdown-link>
            </x-slot>
        </x-dropdown>

        <!-- Create new chat modal -->
        <x-modal maxWidth="sm" name="create-chat" focusable>
            <livewire:chats.create-chat />
        </x-modal>

        <!-- Create new group modal -->
        <x-modal maxWidth="sm" name="create-group" focusable>
            <livewire:chats.create-group />
        </x-modal>
    </div>

    <!-- Chats list -->
    <div class="mt-4 {{ $showAside ? 'mb-12 sm:mb-0' : 'mb-0' }}">
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
                        class="block p-3 rounded cursor-pointer dark:hover:bg-gray-750 hover:bg-gray-50">
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
                                    <p class="text-sm font-medium dark:text-gray-200">
                                        @if ($chat->is_group)
                                            {{ $chat->name }}
                                        @else
                                            {{ $user->name }}
                                        @endif
                                    </p>

                                    @if ($lastMessage)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $lastMessage->created_at->isToday() ? $lastMessage->created_at->format('H:i') : $lastMessage->created_at->format('d/m/Y') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between">
                                    @if ($lastMessage)
                                        <div class="flex items-center gap-1 mt-1">
                                            @if ($isCurrentUser)
                                                <x-chat-check class="dark:text-gray-200" :message="$lastMessage" />
                                            @endif

                                            @if ($chat->is_group && !$isCurrentUser)
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ Str::limit($lastMessage->user->name, 20, '') }}: </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ Str::limit($lastMessage->body, 15) }}
                                                    </p>
                                                </div>
                                            @else
                                                @if ($lastMessage->is_file)
                                                    @if (Str::endsWith($lastMessage->body, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <!-- Display image link -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                                                            class="size-5 dark:text-gray-400">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                        </svg>
                                                    @elseif (Str::endsWith($lastMessage->body, ['pdf']))
                                                        <!-- Display pdf link -->
                                                        <x-pdf-svg width="16px" height="16px" />
                                                    @elseif(Str::endsWith($lastMessage->body, ['docx', 'doc']))
                                                        <!-- Display Word link -->
                                                        <x-word-svg width="24px" height="24px" />
                                                    @else
                                                        <!-- Display global file link -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                                                            class="size-4 dark:text-white">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                        </svg>
                                                    @endif
                                                @else
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ Str::limit($lastMessage->body, 25) }}
                                                    </p>
                                                @endif
                                            @endif
                                        </div>

                                        @if (!$isCurrentUser && $unreadMessages)
                                            <div
                                                class="flex items-center justify-center p-2 text-sm text-white bg-green-500 rounded-full size-4">
                                                <p class="text-xs">{{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                                                </p>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-400">No messages yet</p>
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
