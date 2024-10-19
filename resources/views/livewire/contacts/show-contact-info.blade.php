<x-modal maxWidth="sm" name="show-contact-info-{{ $user->id }}" focusable
    wire:key="show-contact-info-{{ $user->id }}">
    <div class="flex min-h-[50vh]">
        <div class="flex flex-col w-full gap-4 p-6">
            <div class="flex flex-col items-center space-y-2">
                <x-profile-picture :user="$user" class="size-24" />

                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $user->name }}
                    </h2>

                    @php
                        $chat = $user
                            ->chats()
                            ->where('is_group', false)
                            ->whereHas('users', function ($query) {
                                $query->where('users.id', Auth::id());
                            })
                            ->first();
                    @endphp

                    @if ($chat)
                        <button x-on:click="$dispatch('close')" wire:click='selectChat({{ $chat->id }})'
                            class="ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-gray-200">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                            </svg>
                        </button>
                    @else
                        <button x-on:click="$dispatch('close')" wire:click='createChat({{ $user->id }})'
                            class="ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-gray-200">
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
                    <p class="dark:text-gray-400">{{ $user->info ?? 'No info' }}</p>
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
                    <p class="text-gray-500">Activity:</p>
                    @if ($user->last_seen === null)
                        <div class="flex items-center gap-2">
                            <p class="dark:text-gray-400">Online</p>
                            <span class="relative flex size-2">
                                <span
                                    class="absolute inline-flex w-full h-full bg-green-400 rounded-full opacity-75 animate-ping"></span>
                                <span class="relative inline-flex bg-green-500 rounded-full size-2"></span>
                            </span>
                        </div>
                    @else
                        <p class="dark:text-gray-400">{{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</p>
                    @endif
                </div>
            </div>

            <div class="flex justify-between pt-10 mt-auto">
                @if (Auth::user()->contacts()->where('contact_user_id', $user->id)->exists())
                    <x-danger-button wire:click='removeContact({{ $user->id }})' x-on:click="$dispatch('close')">
                        {{ __('Remove contact') }}
                    </x-danger-button>
                @else
                    <x-secondary-button wire:click='addContact({{ $user->id }})' x-on:click="$dispatch('close')">
                        {{ __('Add contact') }}
                    </x-secondary-button>
                @endif
            </div>
        </div>
    </div>
</x-modal>
