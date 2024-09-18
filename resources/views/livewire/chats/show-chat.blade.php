<div class="flex flex-col w-3/4 h-screen bg-gray-100">
    <div class="relative flex-1 p-10 overflow-auto">
        <div class="h-full">
            @if ($chat)
                @forelse($messages as $index => $message)
                    @php
                        $isLastInBlock = $index === count($messages) - 1 || $messages[$index + 1]->user_id !== $message->user_id;
                    @endphp

                    <!-- Chat message -->
                    <div class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }} {{ $isLastInBlock ? 'mb-2' : 'mb-1' }}">
                        <div class="relative max-w-sm p-2 text-sm rounded shadow 
                            {{ $message->user_id === Auth::id() ? 'bg-green-200 text-gray-800' : 'bg-white text-gray-800' }}">
                            
                            <!-- Flex container for text and time/check -->
                            <div class="flex flex-col h-full">
                                <!-- Flex container for message and time -->
                                <div class="flex items-start flex-1 gap-3">
                                    <!-- User profile picture -->
                                    <img src="{{ $message->user->profile_picture }}" alt="{{ $message->user->name }}"
                                        class="object-cover w-8 h-8 rounded-full {{ $message->user_id !== Auth::id() ? 'block' : 'hidden' }}" />

                                    <!-- Message details -->
                                    <div class="flex flex-col flex-1">
                                        <span class="font-semibold" {{ $message->user_id !== Auth::id() ? 'block' : 'hidden' }}>{{ $message->user->name }}</span>
                                        <span>{{ $message->body }}</span>
                                    </div>
                                </div>

                                <!-- Time and check icons -->
                                <div class="flex items-center self-end gap-1 mt-1 text-xs text-gray-500">
                                    <span>{{ $message->created_at->format('H:i') }}</span>
                                    <div class="{{ $message->user_id === Auth::id() ? 'block' : 'hidden' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No messages found</p>
                @endforelse
            @else
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <x-application-logo class="block w-auto text-gray-300 fill-current size-32 dark:text-gray-200" />
                    <p class="mt-2 text-xl text-gray-700">Welcome to Chat App</p>
                    <p class="mt-2 text-sm text-gray-400">Connect with people all around the globe, or just chat a friend!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Message input -->
    <div class="flex items-center px-4 py-2 mt-4 bg-white border-t border-gray-300">
        <button class="mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
            </svg>
        </button>

        <input type="text" placeholder="Type a message here..."
            class="w-full mx-2 border-none focus:outline-none focus:ring-0" />
        <button class="text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path
                    d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
            </svg>
        </button>
    </div>
</div>