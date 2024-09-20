<!-- Chat message -->
<div
    class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }} {{ $isLastInBlock ? 'mb-3' : 'mb-1' }}">

    <!-- Profile picture for group chats if it's the first message in the block -->
    @if ($chat->is_group && !$isCurrentUser && $isFirstInBlock)
        <x-profile-picture :user="$message->user" class="mr-3 size-8" />
    @endif

    <!-- Message details -->
    <div
        class="relative max-w-md p-2 text-sm rounded shadow
                {{ $isCurrentUser ? 'bg-green-200 text-gray-800' : 'bg-white text-gray-800' }}
                {{ !$isFirstInBlock && $chat->is_group ? 'ml-11' : '' }}">

        <!-- Flex container for text and time/check -->
        <div class="flex h-full gap-5">

            <!-- Message content -->
            <div class="flex items-start flex-1 gap-3">
                <div class="flex flex-col flex-1">
                    <!-- Show username only in group chat and if it's the first message in the block -->
                    @if ($chat->is_group && !$isCurrentUser && $isFirstInBlock)
                        <span class="font-semibold">{{ $message->user->name }}</span>
                    @endif
                    <span>{{ $message->body }}</span>
                </div>
            </div>

            <!-- Time and check icons -->
            <div class="flex items-center self-end gap-1 mt-1 text-xs text-gray-500">
                <span>{{ $message->created_at->format('H:i') }}</span>
                @if ($isCurrentUser)
                    <x-chat-check :message="$message" />
                @endif
            </div>
        </div>
    </div>
</div>
