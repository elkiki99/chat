<x-modal maxWidth="sm" name="show-contact-info-{{ $user->id }}" focusable wire:key="show-contact-info-{{ $user->id }}">
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
                        <button x-on:click="$dispatch('close')" wire:click='selectChat({{ $chat->id }})'
                            class="ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                            </svg>
                        </button>
                    @else
                        <button x-on:click="$dispatch('close')" wire:click='createChat({{ $user->id }})'
                            class="ml-2">
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

                <p class="text-gray-700 dark:text-gray-300">
                    {{ $user->email }}
                </p>
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
                <x-secondary-button wire:click='removeContact({{ $user->id }})' x-on:click="$dispatch('close')">
                    {{ __('Remove contact') }}
                </x-secondary-button>
            </div>
        </div>
    </div>
</x-modal>
