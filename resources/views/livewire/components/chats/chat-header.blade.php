<div class="flex w-full h-16 bg-white border-b border-gray-200 dark:border-b-0 dark:bg-gray-800">
    <div class="flex items-center justify-start w-full h-auto p-4">
        @if (!$chat->is_group)
            <div class="flex items-center w-full gap-4 text-sm font-medium">
                <!-- Back to chats -->
                <a class="hover:cursor-pointer" wire:click='backToChats'>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" class="text-gray-700 dark:text-gray-200 size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                    </svg>
                </a>
                <a x-on:click.prevent="$dispatch('open-modal', 'show-contact-info-on-header-{{ $user->id }}')"
                    href="#">
                    <x-profile-picture :user="$user" class="size-10" />
                </a>
                <p class="dark:text-gray-200">{{ $user->name }}</p>

                <!-- Chat actions -->
                <div class="flex gap-4 ml-auto">
                    <!-- Archive chat dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="p-2 mr-3 dark:hover:bg-gray-750 hover:bg-gray-100 hover:rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1" stroke="currentColor" class="size-5 dark:text-gray-200">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @php
                                $isArchived =
                                    Auth::user()
                                        ->chats()
                                        ->where('chats.id', $chat->id)
                                        ->first()->pivot->is_archived ?? false;
                            @endphp

                            <!-- Archive chat -->
                            @if (!$isArchived)
                                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                    wire:click="archiveChat({{ $chat->id }})">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.2" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>Archive chat
                                    </div>
                                </x-dropdown-link>
                            @else
                                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                    wire:click="unarchiveChat({{ $chat->id }})">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.2" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>Unarchive
                                    </div>
                                </x-dropdown-link>
                            @endif

                            <!-- Delete chat -->
                            <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                wire:click="deleteChat({{ $chat->id }})">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.2" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    Delete chat
                                </div>
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                <x-modal maxWidth="sm" name="show-contact-info-on-header-{{ $user->id }}" focusable
                    wire:key="show-contact-info-on-header-{{ $user->id }}">
                    <div class="flex min-h-[50vh]">
                        <div class="flex flex-col w-full gap-4 p-6">
                            <div class="flex flex-col items-center space-y-2">
                                <x-profile-picture :user="$user" class="size-24" />

                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </h2>

                                    @php
                                        $chatExists = Auth::user()
                                            ->chats()
                                            ->where('is_group', false)
                                            ->whereHas('users', function ($query) use ($user) {
                                                $query->where('users.id', $user->id);
                                            })
                                            ->exists();

                                        $chat = $user
                                            ->chats()
                                            ->where('is_group', false)
                                            ->whereHas('users', function ($query) {
                                                $query->where('users.id', Auth::id());
                                            })
                                            ->first();
                                    @endphp

                                    @if ($chatExists)
                                        <button x-on:click="$dispatch('close')"
                                            wire:click='selectChat({{ $chat->id }})' class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="size-6 dark:text-gray-200">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                            </svg>
                                        </button>
                                    @else
                                        <button x-on:click="$dispatch('close')"
                                            wire:click='createChat({{ $user->id }})' class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="size-6 dark:text-gray-200">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <h3 class="text-gray-900 text-md dark:text-gray-100">
                                    {{ $user->username }}
                                </h3>
                            </div>

                            <div class="space-y-2">
                                @php
                                    $authUserGroups = Auth::user()->chats()->where('is_group', true)->pluck('chats.id');
                                    $sharedGroups = $user
                                        ->chats()
                                        ->where('is_group', true)
                                        ->whereIn('chats.id', $authUserGroups)
                                        ->get();
                                @endphp

                                <div class="">
                                    <p class="text-gray-500">Info:</p>
                                    <p class="dark:text-gray-400">Available</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Groups:</p>
                                    @if ($sharedGroups->count() > 0)
                                        <p>
                                            {!! $sharedGroups->map(function ($group) {
                                                    return '<a class="hover:underline hover:cursor-pointer dark:text-gray-400" wire:click=\'selectChat(' .
                                                        $group->id .
                                                        ')\'>' .
                                                        e($group->name) .
                                                        '</a>';
                                                })->implode('<span class="dark:text-gray-400">, </span>') !!}
                                        </p>
                                    @else
                                        <p class="dark:text-gray-400">No groups in common</p>
                                    @endif
                                </div>

                                <div class="">
                                    <p class="text-gray-500">Last conection:</p>
                                    <p class="dark:text-gray-400">10 min ago</p>
                                </div>
                            </div>

                            <div class="flex justify-between pt-10 mt-auto">
                                @if (Auth::user()->contacts()->where('contact_user_id', $user->id)->exists())
                                    <x-danger-button wire:click='removeContact({{ $user->id }})'
                                        x-on:click="$dispatch('close')">
                                        {{ __('Remove contact') }}
                                    </x-danger-button>
                                @else
                                    <x-secondary-button wire:click='addContact({{ $user->id }})'
                                        x-on:click="$dispatch('close')">
                                        {{ __('Add contact') }}
                                    </x-secondary-button>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-modal>
            </div>
        @else
            <div class="flex items-center w-full gap-4 text-sm font-medium">
                <!-- Back to chats -->
                <a class="hover:cursor-pointer" wire:click='backToChats'>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" class="text-gray-700 dark:text-gray-200 size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                    </svg>
                </a>
                <a x-on:click.prevent="$dispatch('open-modal', 'show-group-info-on-header-{{ $chat->id }}')"
                    href="#">
                    <x-chat-image :chat="$chat" class="size-10" />
                </a>
                <p class="dark:text-gray-200">{{ $chat->name }}</p>

                <!-- Chat actions -->
                <div class="flex gap-4 ml-auto">
                    <!-- Archive chat dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="p-2 mr-3 dark:hover:bg-gray-750 hover:bg-gray-100 hover:rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1" stroke="currentColor" class="size-5 dark:text-gray-200">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @php
                                $isArchived =
                                    Auth::user()
                                        ->chats()
                                        ->where('chats.id', $chat->id)
                                        ->first()->pivot->is_archived ?? false;
                            @endphp
                            <!-- Archive chat -->
                            @if (!$isArchived)
                                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                    wire:click="archiveChat({{ $chat->id }})">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.2" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>Archive chat
                                    </div>
                                </x-dropdown-link>
                            @else
                                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                    wire:click="unarchiveChat({{ $chat->id }})">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.2" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>Unarchive
                                    </div>
                                </x-dropdown-link>
                            @endif

                            <!-- Delete chat -->
                            <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                wire:click="deleteChat({{ $chat->id }})">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.2" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    Delete chat
                                </div>
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Group info modal -->
                <x-modal maxWidth="sm" name="show-group-info-on-header-{{ $chat->id }}" focusable
                    wire:key="show-group-info-on-header-{{ $chat->id }}">
                    <div class="flex min-h-[50vh]">
                        <div class="flex flex-col w-full gap-4 p-6">
                            <div class="flex flex-col items-center space-y-2">
                                <x-chat-image :chat="$chat" class="size-24" />

                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ $chat->name }}
                                </h2>
                            </div>

                            <div class="items-start">
                                <p class="text-gray-500">Created:</p>
                                <p class="dark:text-gray-400">{{ $chat->created_at->format('d/m/y') }}</p>
                            </div>

                            <div class="items-start">
                                <p class="text-gray-500">
                                    {{ __('Members') }}
                                </p>

                                @foreach ($chat->users as $user)
                                    @if ($user->id !== Auth::id())
                                        @php
                                            $userChat = $user
                                                ->chats()
                                                ->where('is_group', false)
                                                ->whereHas('users', function ($query) {
                                                    $query->where('users.id', Auth::id());
                                                })
                                                ->first();
                                        @endphp

                                        <a 
                                            wire:click="selectChat({{ $userChat->id }})"
                                            x-on:click="$dispatch('close')"
                                            class="p-3 cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <x-profile-picture :user="$user" class="size-12" />

                                                <div class="flex-1 mx-2">
                                                    <div class="flex items-center justify-between">
                                                        <!-- User name -->
                                                        <p class="text-sm font-medium dark:text-gray-200">
                                                            {{ $user->name }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>

                            <div class="flex justify-between pt-10 mt-auto">
                                <x-danger-button wire:click='leaveGroup({{ $chat->id }})'
                                    x-on:click="$dispatch('close')">
                                    {{ __('Leave group') }}
                                </x-danger-button>
                            </div>
                        </div>
                    </div>
                </x-modal>
            </div>
        @endif
    </div>
</div>
