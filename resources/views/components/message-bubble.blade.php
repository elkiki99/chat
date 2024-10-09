<div
    class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }} {{ $isLastInBlock ? 'mb-3' : 'mb-1' }}">

    @if ($chat->is_group && !$isCurrentUser && $isFirstInBlock)
        <x-profile-picture :user="$message->user" class="mr-3 size-8" />
    @endif

    <div
        class="relative max-w-md p-2 text-sm rounded shadow
                {{ $isCurrentUser ? 'bg-green-200 text-gray-800 dark:bg-emerald-800 dark:text-white' : 'bg-white text-gray-800 dark:bg-gray-800 dark:text-gray-200' }}
                {{ !$isFirstInBlock && $chat->is_group ? 'ml-11' : '' }}">

        <div class="flex h-full gap-5">
            <div class="flex items-start flex-1 gap-3">
                <div class="flex flex-col flex-1">

                    @if ($chat->is_group && !$isCurrentUser && $isFirstInBlock)
                        <span class="font-semibold">{{ $message->user->name }}</span>
                    @endif

                    @if ($message->is_file)
                        @if (Str::endsWith($message->body, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <!-- Display image -->
                            <div class="relative w-full h-full">
                                <img src="{{ asset('storage/uploads/' . $message->body) }}" alt="Imagen enviada"
                                    class="w-full h-full rounded-lg">
                                <div class="absolute flex items-center gap-1 text-xs text-gray-500 bottom-2 right-2">
                                    <span class="text-white">{{ $message->created_at->format('H:i') }}</span>

                                    @if ($isCurrentUser)
                                        <x-chat-check class="text-white" :message="$message" />
                                    @endif
                                </div>
                            </div>
                        @elseif (Str::endsWith($message->body, ['pdf']))
                            <!-- Display PDF link -->
                            <div class="flex flex-col items-end ">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <a href="{{ asset('storage/uploads/' . $message->body) }}" target="_blank"
                                        class="flex items-center">
                                        <x-pdf-svg />
                                        <span class="ml-1">{{ pathinfo($message->body, PATHINFO_BASENAME) }}</span>
                                    </a>
                                </div>
                            </div>
                        @elseif(Str::endsWith($message->body, ['docx', 'doc']))
                            <!-- Display Word link -->
                            <div class="flex flex-col items-end ">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <a href="{{ asset('storage/uploads/' . $message->body) }}" target="_blank"
                                        class="flex items-center">
                                        <x-word-svg />
                                        <span class="ml-1">{{ pathinfo($message->body, PATHINFO_BASENAME) }}</span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- Display global file link -->
                            <div class="flex flex-col items-end ">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <a href="{{ asset('storage/uploads/' . $message->body) }}" target="_blank"
                                        class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1" stroke="currentColor" class="size-[24px]">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                        <span class="ml-1">{{ pathinfo($message->body, PATHINFO_BASENAME) }}</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <span>{{ $message->body }}</span>
                    @endif
                </div>
            </div>

            <!-- Time and check icons -->
            @if (Str::endsWith($message->body, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
            @else
                <div class="flex items-center self-end gap-1 mt-1 text-xs text-gray-500 dark:text-gray-400">
                    <span>{{ $message->created_at->format('H:i') }}</span>
                    @if ($isCurrentUser)
                        <x-chat-check :message="$message" />
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
