<div class="flex flex-col w-full h-screen pl-0 bg-gray-100 dark:bg-gray-900 sm:pl-12 md:pl-0">

    @if ($chat)
        <livewire:components.chats.chat-header :chat="$chat" :user="$user" />

        <div id="chat-container" class="relative flex-1 overflow-auto">
            <div class="p-6">
                <div x-intersect="$wire.loadMoreMessages()"></div>

                <div class="h-full">
                    @forelse($messages as $index => $message)
                        @php
                            $isCurrentUser = $message->user_id === Auth::id();
                            $isLastInBlock =
                                $index === count($messages) - 1 || $messages[$index + 1]->user_id !== $message->user_id;
                            $isFirstInBlock = $index === 0 || $messages[$index - 1]->user_id !== $message->user_id;
                        @endphp

                        <x-message-bubble x-cloak :chat="$chat" :message="$message" :isLastInBlock="$isLastInBlock" :isFirstInBlock="$isFirstInBlock"
                            :isCurrentUser="$isCurrentUser" />
                    @empty
                        <x-lobby />
                    @endforelse
                </div>
            </div>
        </div>

        <livewire:messages.send-message />
    @else
        <div class="relative hidden h-full md:flex">
            <x-lobby />
        </div>
    @endif
</div>

<script>
    function scrollToBottom(container) {
        if (container) {
            requestAnimationFrame(() => {
                container.scrollTop = container.scrollHeight;
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        let container = document.querySelector('#chat-container');
        scrollToBottom(container);

        window.addEventListener('scrollDown', () => {
            if (container) {
                scrollToBottom(container);
            }
        });
    });
</script>
