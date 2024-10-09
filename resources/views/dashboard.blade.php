<x-app-layout>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <livewire:layouts.sidebar />
        
        <!-- Chats -->
        <div class="flex flex-col flex-grow sm:flex-row">
            <livewire:layouts.chat-layout /> <!-- Este también debe ocupar espacio -->
            <livewire:chats.show-chat  /> <!-- Este también debe ocupar espacio -->
        </div>
    </div>
</x-app-layout>