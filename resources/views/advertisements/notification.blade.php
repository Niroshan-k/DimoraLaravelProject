{{-- filepath: resources/views/advertisements/notification.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl mt-20 font-bold">Notifications</h1>
    </x-slot>

    <div class="max-w-4xl mt-20 mx-auto px-4">
        <ul class="space-y-4">
            @forelse($notifications as $notification)
                <li class="bg-white shadow rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <div class="text-gray-700 font-medium flex items-center gap-2">
                            <!-- Bell Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{ is_array($notification['message'] ?? '') || is_object($notification['message'] ?? '') 
                                ? json_encode($notification['message'] ?? '') 
                                : ($notification['message'] ?? '') }}
                        </div>
                        <span class="text-sm text-gray-400 mt-1">
                            @php
                                $createdAt = $notification['created_at'] ?? '';
                                if (is_array($createdAt) && isset($createdAt['date'])) {
                                    $createdAt = $createdAt['date'];
                                }
                                if (is_array($createdAt) || is_object($createdAt)) {
                                    $createdAt = '-';
                                }
                            @endphp
                            {{ $createdAt ?: '-' }}
                        </span>
                        @php
                            // Handle advertisement_id as string or int
                            $adId = $notification['advertisement_id'] ?? '';
                            if (is_array($adId) && isset($adId['$numberInt'])) {
                                $adId = $adId['$numberInt'];
                            } elseif (is_object($adId)) {
                                $adId = (string) $adId;
                            }
                        @endphp
                        <a href="{{ url('/advertisement/' . $adId) }}"
                           class="inline-block mt-2 text-blue-600 hover:underline font-semibold">
                            View Advertisement
                        </a>
                    </div>
                    <!-- Close/Mark as Read Icon -->
                    <form method="POST" action="{{ url('/notifications/mark-read') }}" class="ml-4">
                        @csrf
                        <input type="hidden" name="notification_id" value="{{ is_array($notification['_id'] ?? '') && isset($notification['_id']['$oid']) ? $notification['_id']['$oid'] : ($notification['_id'] ?? '') }}">
                        <button type="submit" title="Mark as read" class="text-gray-400 hover:text-red-500 transition">
                            <!-- X Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                </li>
            @empty
                <li class="text-gray-500 text-center py-8 bg-white rounded shadow">No notifications found.</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>