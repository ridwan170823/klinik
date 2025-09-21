@if (Auth::check())
  @php
    $notifications = Auth::user()->notifications()->latest()->limit(5)->get();
    $unreadCount = Auth::user()->unreadNotifications->count();
  @endphp
  <li class="nav-item dropdown no-arrow mx-1 notification-dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-bell fa-fw"></i>
      @if ($unreadCount > 0)
        <span class="badge badge-danger badge-counter">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
      @endif
    </a>
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
         aria-labelledby="alertsDropdown">
      <h6 class="dropdown-header">Notifikasi Terbaru</h6>
      @forelse ($notifications as $notification)
        @php
          $data = $notification->data;
          $isUnread = is_null($notification->read_at);
        @endphp
        <a class="dropdown-item d-flex align-items-start {{ $isUnread ? 'bg-light' : '' }}"
           href="{{ $data['action_url'] ?? '#' }}">
          <div class="mr-3">
            <div class="icon-circle bg-primary text-white">
              <i class="fas {{ $data['icon'] ?? 'fa-calendar-check' }}"></i>
            </div>
          </div>
          <div>
            <div class="small text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
            <span class="font-weight-bold d-block">{{ $data['title'] ?? 'Notifikasi' }}</span>
            <span class="small text-gray-600">{{ $data['message'] ?? '' }}</span>
          </div>
        </a>
      @empty
        <div class="px-3 py-4 text-center text-muted small">Belum ada notifikasi.</div>
      @endforelse
      @if ($notifications->isNotEmpty())
        <div class="dropdown-divider"></div>
        <form method="POST" action="{{ route('notifications.read') }}" class="px-3 pb-2">
          @csrf
          @foreach ($notifications as $notification)
            <input type="hidden" name="ids[]" value="{{ $notification->id }}">
          @endforeach
          <button type="submit" class="btn btn-link btn-sm px-0">Tandai sudah dibaca</button>
        </form>
      @endif
    </div>
  </li>
@endif