@if ($message->seenByAll())
     <!-- Opción 2: Mensaje visto -->
     <svg class="size-5" fill="#3B82F6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
         <line x1="13.22" y1="16.5" x2="21" y2="7.5"
             style="fill: none; stroke: #0088ff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 1;"></line>
         <polyline points="3 11.88 7 16.5 14.78 7.5"
             style="fill: none; stroke: #0088ff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 1;">
         </polyline>
     </svg>
 @else
     <!-- Opción 1: Mensaje no visto -->
     <svg class="size-5" fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
         <line x1="13.22" y1="16.5" x2="21" y2="7.5"
             style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 1;">
         </line>
         <polyline points="3 11.88 7 16.5 14.78 7.5"
             style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 1;">
         </polyline>
     </svg>
 @endif
