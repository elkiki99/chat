<div class="">
    <!-- Chat actions -->

    <div
        class="flex items-center px-4 py-2 bg-white border-t border-gray-300 dark:border-t-0 dark:bg-gray-800">
        <!-- Send file clip button -->
        <button x-on:click="$dispatch('open-modal', 'send-file')"
            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-750 hover:rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                class="text-gray-700 dark:text-gray-200 size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
            </svg>
        </button>

        <!-- Send file modal -->
        <x-modal maxWidth="sm" name="send-file" focusable>
            <div class="p-6 space-y-2">
                <livewire:dropzone wire:model="files" :multiple="true" />
                <x-primary-button x-on:click="$dispatch('close')" wire:click="sendFile"
                    class="mt-4">Send</x-primary-button>

                @if ($errors->has('files.*'))
                    <div class="text-red-500">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-modal>

        <!-- Message input -->
        <form wire:submit.prevent="sendMessage" class="flex w-full">
            <input @keydown="typing = true; $dispatch('userTyping', { typingUserId: @js(Auth::id()) })"
                @keyup.debounce.1000="typing = false; $dispatch('userStoppedTyping')" type="text"
                wire:model.lazy="body" placeholder="Type a message here..."
                class="w-full mx-2 border-none focus:outline-none dark:placeholder:text-gray-400 dark:bg-gray-800 dark:text-gray-100 focus:ring-0" />
            <button type="submit" class="text-green-600 dark:text-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path
                        d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
                </svg>
            </button>
        </form>
    </div>
</div>