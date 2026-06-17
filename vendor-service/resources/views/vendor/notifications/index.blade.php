@extends('layouts.vendor')

@section('header_title', 'Notifikasi Pesanan')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Header Page -->
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Notifikasi Pesanan</h3>
            <p class="text-gray-500 text-sm">Riwayat pesanan masuk dan instruksi pengiriman Anda.</p>
        </div>
        <span class="px-3 py-1 text-xs font-bold bg-vendor-100 text-vendor-700 border border-vendor-200 rounded-full" id="unread-badge">
            <span id="unread-count">{{ $notifications->whereNull('read_at')->count() }}</span> Belum Dibaca
        </span>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100" id="notifications-list-container">
            @forelse($notifications as $notif)
                @php
                    $isUnread = is_null($notif->read_at);
                @endphp
                <div class="p-6 transition-all duration-300 flex items-start gap-4 hover:bg-gray-50/50 {{ $isUnread ? 'bg-vendor-50/30' : '' }}" id="notif-row-{{ $notif->id }}">
                    <!-- Status Icon/Dot -->
                    <div class="shrink-0 mt-1">
                        @if($isUnread)
                            <div class="w-3 h-3 bg-vendor-500 rounded-full animate-pulse shadow-sm shadow-vendor-500/50" id="notif-dot-{{ $notif->id }}"></div>
                        @else
                            <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                        @endif
                    </div>

                    <!-- Message Body -->
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                            @if($isUnread)
                                <span class="px-2 py-0.5 text-[10px] font-extrabold bg-vendor-100 text-vendor-700 rounded-md uppercase tracking-wider" id="new-tag-{{ $notif->id }}">Baru</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-800 {{ $isUnread ? 'font-semibold' : 'text-gray-500' }}" id="notif-msg-{{ $notif->id }}">
                            {{ $notif->message }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="shrink-0" id="notif-action-{{ $notif->id }}">
                        @if($isUnread)
                            <button onclick="markNotificationAsRead({{ $notif->id }})" class="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:text-vendor-600 hover:border-vendor-200 hover:bg-vendor-50 rounded-xl text-xs font-bold transition shadow-sm" title="Tandai Dibaca">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                Tandai Dibaca
                            </button>
                        @else
                            <span class="text-xs text-gray-400 font-bold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                Dibaca
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-20 text-gray-400">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto opacity-20 text-vendor-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <p class="font-bold text-gray-500 text-sm">Belum ada notifikasi pesanan</p>
                    <p class="text-xs text-gray-400 mt-1">Setiap ada pesanan baru dari Admin, Anda akan menerimanya di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    async function markNotificationAsRead(id) {
        try {
            const response = await fetch(`/vendor/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const result = await response.json();
            if (response.ok && result.success) {
                // Update styling of the row to read state
                const row = document.getElementById(`notif-row-${id}`);
                if (row) {
                    row.classList.remove('bg-vendor-50/30');
                }

                // Update status dot
                const dot = document.getElementById(`notif-dot-${id}`);
                if (dot) {
                    dot.className = 'w-3 h-3 bg-gray-300 rounded-full';
                    dot.removeAttribute('id');
                }

                // Remove new tag
                const newTag = document.getElementById(`new-tag-${id}`);
                if (newTag) {
                    newTag.remove();
                }

                // Update text font weight
                const msg = document.getElementById(`notif-msg-${id}`);
                if (msg) {
                    msg.classList.remove('font-semibold');
                    msg.classList.add('text-gray-500');
                }

                // Update action column
                const action = document.getElementById(`notif-action-${id}`);
                if (action) {
                    action.innerHTML = `
                        <span class="text-xs text-gray-400 font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            Dibaca
                        </span>
                    `;
                }

                // Update badge counts
                const countBadge = document.getElementById('unread-count');
                if (countBadge) {
                    let count = parseInt(countBadge.innerText);
                    count = Math.max(0, count - 1);
                    countBadge.innerText = count;
                }
            }
        } catch (error) {
            console.error('Gagal memperbarui notifikasi:', error);
        }
    }
</script>
@endpush
