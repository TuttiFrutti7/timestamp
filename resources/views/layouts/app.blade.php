<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Timestamp</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <header>
        <nav class="bg-gray-800 p-4 flex items-center">
            <div class="flex items-center space-x-8">
                <form action="{{ route('set-locale') }}" method="POST" class="inline">
                    @csrf
                    <select name="locale" onchange="this.form.submit()">
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                        <option value="lv" {{ app()->getLocale() == 'lv' ? 'selected' : '' }}>LV</option>
                    </select>
                </form>
                <a href="{{ route('dashboard') }}" class="text-white hover:text-yellow-300">Dashboard</a>
                <a href="{{ route('posts.index') }}" class="text-white hover:text-yellow-300">All Posts</a>
                <a href="{{ route('posts.community') }}" class="text-white hover:text-yellow-300">Community Posts</a>
                <a href="{{ route('search') }}" class="text-white hover:text-yellow-300">Search</a>
                <a href="{{ route('profile.edit') }}" class="text-white hover:text-yellow-300">Profile</a>
                @auth
                    @php
                        $user = auth()->user();
                        $remaining = null;
                        if ($user && $user->timer) {
                            $today = now()->startOfDay();
                            $logs = $user->usageLogs()->where('login_time', '>=', $today)->get();
                            $total = 0;
                            foreach ($logs as $log) {
                                if ($log->logout_time) {
                                    $total += $log->login_time->diffInSeconds($log->logout_time) / 60;
                                } else {
                                    $total += $log->login_time->diffInSeconds(now()) / 60;
                                }
                            }
                            $remaining = max(0, $user->timer->limit - $total);
                        }
                        $remainingSeconds = isset($remaining) ? round($remaining * 60) : 0;
                    @endphp
                    @if(!is_null($remaining))
                        <span class="text-yellow-300 font-bold ml-4" id="timer-remaining"
                            data-seconds="{{ $remainingSeconds }}">
                            Time left today: {{ $remaining > 1 ? floor($remaining) . ' min' : ($remainingSeconds . ' sec') }}
                        </span>
                    @endif
                @endauth
            </div>
            <div class="flex items-center space-x-4 ml-auto">
                @auth
                    <span class="text-gray-300">
    Logged in as
    <span class="font-bold text-yellow-300">{{ auth()->user()->username }}</span>
</span>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-red-400 hover:text-red-600">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="text-white hover:text-yellow-300">Login</a>
                    <a href="{{ route('register') }}" class="text-white hover:text-yellow-300">Register</a>
                @endguest
            </div>
        </nav>
    </header>

    <main class="container mx-auto mt-8 p-4 bg-white rounded shadow">
        @yield('content')
    </main>

    <footer class="text-center p-4 bg-gray-200 text-gray-600 mt-8">
        <p>© 2025 Timestamp</p>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('commentEdit', (id, originalContent) => ({
                editing: false,
                originalContent: originalContent,
                editedContent: originalContent,

                async submitEdit() {
                    const response = await fetch(`/comments/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: this.editedContent })
                    });

                    if (response.ok) {
                        this.editing = false;
                        this.originalContent = this.editedContent;
                    } else {
                        alert('Failed to update comment');
                    }
                },

                cancelEdit() {
                    this.editedContent = this.originalContent;
                    this.editing = false;
                },

                deleteComment() {
                    if (!confirm('Delete this comment?')) return;

                    fetch(`/comments/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            document.getElementById(`comment-${id}`).remove();
                        } else {
                            alert('Failed to delete comment');
                        }
                    });
                }
            }))
        });
        // komentāru izvadi sekundēs kad laiks <60s palicis
        document.addEventListener('DOMContentLoaded', function () {
            const timerElem = document.getElementById('timer-remaining');
            if (!timerElem) return;
            let seconds = parseInt(timerElem.dataset.seconds);

            function updateTimer() {
                if (seconds <= 0) {
                    timerElem.textContent = "Time left today: 0 sec";
                    return;
                }
                if (seconds > 60) {
                    timerElem.textContent = "Time left today: " + Math.floor(seconds / 60) + " min";
                } else {
                    timerElem.textContent = "Time left today: " + seconds + " sec";
                }
                seconds--;
                setTimeout(updateTimer, 1000);
            }
            updateTimer();
        });
    </script>
</body>
</html>