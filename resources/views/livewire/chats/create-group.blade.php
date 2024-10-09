<form wire:submit.prevent="createGroup" class="p-6" enctype="multipart/form-data">
    <input type="text" wire:model.live="name" placeholder="New group name..."
        class="w-full text-gray-100 border-none dark:placeholder:text-gray-400 dark:bg-gray-800 focus:outline-none focus:ring-0" />

    @error('name')
        <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="text-sm text-red-500">{{ $message }}</span>
    @enderror

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
                placeholder="Search contacts...">
        </div>

        <!-- Contacts -->
        <div class="mt-4">
            <ul>
                @forelse ($contacts as $user)
                    <li>
                        <div class="flex items-center gap-2 p-3 dark:hover:bg-gray-750 hover:bg-gray-100">
                            <!-- User image -->
                            <x-profile-picture :user="$user" class="size-10" />

                            <div class="flex-1 mx-2">
                                <div class="flex items-center justify-between">
                                    <!-- User name -->
                                    <p class="text-sm font-medium dark:text-gray-200">
                                        {{ $user->name }}
                                    </p>
                                </div>
                            </div>

                            @if ($selectedContacts->contains($user->id))
                                <button type="button" wire:click="toggleContactSelect({{ $user->id }})"
                                    class="block rounded cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="text-red-500 size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            @else
                                <button type="button" wire:click="toggleContactSelect({{ $user->id }})"
                                    class="block rounded cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="text-green-500 size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="text-gray-500">No contacts found</li>
                @endforelse
            </ul>
        </div>
    </div>
    
    <div class="px-2 py-6">
        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300">Group image</label>

        {{-- <x-text-input id="chat_image" type="file" wire:model="chat_image"
            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" /> --}}

        <livewire:dropzone wire:model="chat_image" :multiple="false" />

        {{-- @if ($chat_image)
            <div class="mt-4">
                <img src="{{ $chat_image->temporaryUrl() }}" alt="Image Preview"
                    class="object-cover p-1 mr-2 rounded-full shadow size-32" />
            </div>
        @endif --}}

        @error('chat_image')
            <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-red-500">
                {{ $message }}
            </span>
        @enderror
    </div>
    
    <div class="flex justify-between mt-6">
        <x-danger-button type="button" x-on:click="$dispatch('close')">
            {{ __('Cancel') }}
        </x-danger-button>

        @if ($selectedContacts->isNotEmpty() && $name)
            <x-secondary-button type="submit" class="block rounded cursor-pointer">
                Create group
            </x-secondary-button>
        @endif
    </div>
</form>
