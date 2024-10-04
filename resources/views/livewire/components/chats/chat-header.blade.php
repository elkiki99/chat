<div class="flex w-full h-16 bg-white border-b border-gray-200">
    <div class="flex items-center justify-start w-full h-auto p-4">
        @if (!$chat->is_group)
            <div class="flex items-center w-full gap-4 text-sm font-medium">
                <a x-on:click.prevent="$dispatch('open-modal', 'show-contact-info-on-header-{{ $user->id }}')"
                    href="#">
                    <x-profile-picture :user="$user" class="size-10" />
                </a>
                <p>{{ $user->name }}</p>

                <div class="flex gap-4 ml-auto">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </a>

                    <!-- Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="p-2 mr-3 hover:bg-gray-100 hover:rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1" stroke="currentColor" class="size-5">
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
                            @if (!$isArchived)
                                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                    wire:click="archiveChat({{ $chat->id }})">
                                    Archive chat
                                </x-dropdown-link>
                            @else
                                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                    wire:click="unarchiveChat({{ $chat->id }})">
                                    Unarchive
                                </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

                <x-modal maxWidth="lg" name="show-contact-info-on-header-{{ $user->id }}" focusable
                    wire:key="show-contact-info-on-header-{{ $user->id }}">
                    <div class="flex min-h-[50vh] w-full">
                        <aside class="w-1/3 p-6 bg-gray-200">
                            {{-- <a class="flex flex-col hover:cursor-pointer">
                            <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                                Summary
                            </div>
                        </a>
                        <a class="flex flex-col hover:cursor-pointer">
                            <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                                Multimedia
                            </div>
                        </a>
                        <a class="flex flex-col hover:cursor-pointer">
                            <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                                Files
                            </div>
                        </a>
                        <a class="flex flex-col hover:cursor-pointer">
                            <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                                Groups
                            </div>
                        </a> --}}
                        </aside>

                        <div class="flex flex-col w-3/4 gap-4 p-6">
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
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                            </svg>
                                        </button>
                                    @else
                                        <button x-on:click="$dispatch('close')"
                                            wire:click='createChat({{ $user->id }})' class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <h3 class="text-gray-900 text-md dark:text-gray-100">
                                    {{ $user->username }}
                                </h3>

                                {{-- <p class="text-gray-700 dark:text-gray-300">
                                    {{ $user->email }}
                                </p> --}}
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
                                    <p>Available</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Groups:</p>
                                    @if ($sharedGroups->count() > 0)
                                        <p>
                                            {!! $sharedGroups->map(function ($group) {
                                                    return '<a class="hover:underline" href="javascript:void(0);" wire:click=\'selectChat(' .
                                                        $group->id .
                                                        ')\'>' .
                                                        e($group->name) .
                                                        '</a>';
                                                })->implode(', ') !!}
                                        </p>
                                    @else
                                        <p>No groups in common</p>
                                    @endif
                                </div>

                                <div class="">
                                    <p class="text-gray-500">Last conection:</p>
                                    <p>10 min ago</p>
                                </div>
                            </div>

                            <div class="flex justify-between pt-10 mt-auto">
                                <x-secondary-button wire:click='removeContact({{ $user->id }})'
                                    x-on:click="$dispatch('close')">
                                    {{ __('Remove contact') }}
                                </x-secondary-button>

                                {{-- <x-danger-button x-on:click="$dispatch('close')">
                            {{ __('Block') }}
                        </x-danger-button> --}}
                            </div>
                        </div>
                    </div>
                </x-modal>
            </div>
        @else
            <div class="flex items-center w-full gap-4 text-sm font-medium">
                <a x-on:click.prevent="$dispatch('open-modal', 'show-group-info-on-header-{{ $chat->id }}')"
                    href="#">
                    <x-chat-image :chat="$chat" class="size-10" />
                </a>
                <p>{{ $chat->name }}</p>

                <div class="flex gap-4 ml-auto">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </a>

                    <!-- Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="p-2 mr-3 hover:bg-gray-100 hover:rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1" stroke="currentColor" class="size-5">
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
                            @if (!$isArchived)
                                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                    wire:click="archiveChat({{ $chat->id }})">
                                    Archive chat
                                </x-dropdown-link>
                            @else
                                <x-dropdown-link class="hover:cursor-pointer" x-data=""
                                    wire:click="unarchiveChat({{ $chat->id }})">
                                    Unarchive
                                </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

                <x-modal maxWidth="lg" name="show-group-info-on-header-{{ $chat->id }}" focusable
                    wire:key="show-group-info-on-header-{{ $chat->id }}">
                    <div class="flex min-h-[50vh] w-full">
                        <aside class="w-1/3 p-6 bg-gray-200">
                            {{-- <a class="flex flex-col hover:cursor-pointer">
                        <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                            Summary
                        </div>
                    </a>
                    <a class="flex flex-col hover:cursor-pointer">
                        <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                            Multimedia
                        </div>
                    </a>
                    <a class="flex flex-col hover:cursor-pointer">
                        <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                            Files
                        </div>
                    </a>
                    <a class="flex flex-col hover:cursor-pointer">
                        <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                            Groups
                        </div>
                    </a> --}}
                        </aside>

                        <div class="flex flex-col w-3/4 gap-4 p-6">
                            <div class="flex flex-col items-center space-y-2">
                                <x-chat-image :chat="$chat" class="size-24" />

                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ $chat->name }}
                                </h2>
                            </div>

                            <div class="items-start">
                                <p class="text-gray-500">Created:</p>
                                <p>{{ $chat->created_at->format('d/m/y') }}</p>
                            </div>

                            <div class="items-start">
                                <p class="text-gray-500">
                                    {{ __('Members') }}
                                </p>

                                @php
                                    $chatExists = Auth::user()
                                        ->chats()
                                        ->where('is_group', false) // Asegúrate de que 'is_group' sea la condición correcta.
                                        ->whereHas('users', function ($query) use ($user) {
                                            // Verifica que $user esté definido y que se pase correctamente.
                                            if ($user) {
                                                $query->where('users.id', $user->id);
                                            }
                                        })
                                        ->exists();
                                @endphp

                                @foreach ($chat->users as $user)
                                    @if ($chatExists)
                                        <a wire:click='selectChatOnGroupMembers({{ $chat->id }})' class="p-3 cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <x-profile-picture :user="$user" class="size-12" />

                                                <div class="flex-1 mx-2">
                                                    <div class="flex items-center justify-between">
                                                        <!-- User name -->
                                                        <p class="text-sm font-medium">
                                                            {{ $user->name }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <a wire:click='createChatOnGroupMembers({{ $user->id }})' class="p-3 cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <x-profile-picture :user="$user" class="size-12" />

                                                <div class="flex-1 mx-2">
                                                    <div class="flex items-center justify-between">
                                                        <!-- User name -->
                                                        <p class="text-sm font-medium">
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

                                {{-- <x-danger-button x-on:click="$dispatch('close')">
                        {{ __('Block') }}
                    </x-danger-button> --}}
                            </div>
                        </div>
                    </div>
                </x-modal>
            </div>
        @endif
    </div>
</div>
