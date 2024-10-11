<x-app-layout>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <livewire:layouts.sidebar />
        
        <!-- Chats -->
        <div class="flex flex-col flex-grow md:flex-row">
            <livewire:layouts.chat-layout />
            <livewire:chats.show-chat  />
        </div>
    </div>
</x-app-layout>