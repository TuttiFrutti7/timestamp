<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Timestamp</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('posts.index') }}">Posts</a>
            <a href="{{ route('posts.create') }}">Create</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>© 2025 Timestamp</p>
    </footer>
    <!-- huh? -->
    @guest
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    @endguest

    @auth
        <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    @endauth

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
    </script>
</body>