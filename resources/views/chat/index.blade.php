@extends('layouts.app')

@section('content')
<div class="container-xxl mt-8">
    <div class="d-flex border rounded overflow-hidden" style="min-height: 580px; border-color: #e5e7eb !important;">

        {{-- SIDEBAR --}}
        <div class="d-flex flex-column border-end" style="width: 260px; min-width: 260px; border-color: #e5e7eb !important;">

            <div class="p-4 border-bottom" style="border-color: #e5e7eb !important;">
                <div class="fw-semibold text-gray-800 mb-3" style="font-size: 13px;">Messages</div>
                <div class="position-relative">
                    <i class="ki-duotone ki-magnifier fs-5 text-gray-400 position-absolute top-50 translate-middle-y ms-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text"
                           class="form-control form-control-sm bg-light border-0"
                           placeholder="Search contacts…"
                           style="padding-left: 32px; font-size: 13px;" />
                </div>
            </div>

            <div class="overflow-auto flex-grow-1">
                @forelse($users as $user)
                    @php
                        $unread = \App\Models\Message::where('sender_id', $user->id)
                                    ->where('receiver_id', Auth::id())
                                    ->where('is_read', false)
                                    ->count();

                        $lastMsg = \App\Models\Message::where(function($q) use ($user) {
                                        $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
                                    })->orWhere(function($q) use ($user) {
                                        $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
                                    })->latest()->first();

                        $avatarColors = ['bg-light-primary text-primary', 'bg-light-success text-success', 'bg-light-warning text-warning', 'bg-light-info text-info'];
                        $colorClass = $avatarColors[$user->id % count($avatarColors)];
                    @endphp

                    <a href="{{ route('chat.show', $user->id) }}"
                       class="d-flex align-items-center gap-3 px-4 py-3 text-decoration-none border-start border-2 {{ request()->route('id') == $user->id ? 'bg-light border-primary' : 'border-transparent' }}"
                       style="border-color: {{ request()->route('id') == $user->id ? '#185FA5' : 'transparent' }} !important;">

                        <div class="symbol symbol-35px symbol-circle flex-shrink-0">
                            <span class="symbol-label {{ $colorClass }} fw-semibold" style="font-size: 12px;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-gray-800 text-truncate" style="font-size: 13px;">{{ $user->name }}</div>
                            @if($lastMsg)
                                <div class="text-muted text-truncate" style="font-size: 12px;">{{ Str::limit($lastMsg->message, 28) }}</div>
                            @else
                                <div class="text-muted" style="font-size: 12px;">{{ $user->email }}</div>
                            @endif
                        </div>

                        <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                            @if($lastMsg)
                                <span class="text-muted" style="font-size: 11px;">{{ $lastMsg->created_at->diffForHumans(null, true) }}</span>
                            @endif
                            @if($unread > 0)
                                <span class="badge rounded-pill text-white" style="background:#185FA5; font-size: 11px; padding: 2px 7px;">{{ $unread }}</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="text-center text-muted py-10" style="font-size: 13px;">No contacts found.</div>
                @endforelse
            </div>
        </div>

        {{-- MAIN AREA: empty state --}}
        <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center p-10">
            <div class="rounded-circle d-flex align-items-center justify-content-center mb-5"
                 style="width:56px;height:56px;background:#f3f4f6;">
                <i class="ki-duotone ki-message-text-2 fs-2x text-gray-400">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
            </div>
            <div class="fw-semibold text-gray-700 mb-2" style="font-size: 15px;">Your messages</div>
            <div class="text-muted" style="font-size: 13px;">Select a contact from the left to start a conversation.</div>
        </div>

    </div>
</div>
@endsection
