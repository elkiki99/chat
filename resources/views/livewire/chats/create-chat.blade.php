<div class="mt-4">
    <ul>
        @forelse ($contacts as $user)
            <li>
                <a x-on:click="$dispatch('close')" wire:click="createChat({{ $user->id }})"
                    class="block p-3 rounded cursor-pointer hover:bg-gray-50">
                    <div class="flex items-center gap-2">
                        <!-- User image -->
                        <x-profile-picture :user="$user" class="size-10" />

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
            </li>
        @empty
            <li class="p-4 text-gray-500">No contacts found</li>
        @endforelse
    </ul>
</div>