<div class="mt-4">
    <!-- Search users -->
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
            placeholder="Search a contact...">
    </div>

    <div class="mt-4">
        <ul>
            @forelse ($contacts as $user)
                <li>
                    <div class="flex items-center gap-2 p-3 hover:bg-gray-100">
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
                        
                        <button x-on:click="$dispatch('close')" wire:click="createContact({{ $user->id }})"
                            class="block rounded cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="text-gray-700 size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                </li>
            @empty
                {{-- <li>No contacts found.</li> --}}
            @endforelse
        </ul>
    </div>
</div>
