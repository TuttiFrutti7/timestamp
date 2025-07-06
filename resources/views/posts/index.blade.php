@extends('layouts.app')

@section('content')
    @include('components.alert')

    <div x-data="postLoader('{{ route('posts.index') }}')" x-init="loadInitial" @scroll.window="checkScroll">
        <div id="posts-list">
            @foreach($posts as $post)
                <x-post :post="$post" />
            @endforeach
        </div>
    </div>

    <a href="{{ route('posts.create') }}"
       class="fixed bottom-8 right-8 bg-yellow-400 hover:bg-yellow-500 text-white rounded-full shadow-lg w-16 h-16 flex items-center justify-center text-4xl z-50"
       title="Create Post">
        +
    </a>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('postLoader', (url) => ({
                nextPage: url + '?page=2',
                loading: false,

                async loadInitial() {
                    this.loading = false;
                },

                async checkScroll() {
                    const scrollHeight = document.documentElement.scrollHeight;
                    const scrollTop = window.pageYOffset + window.innerHeight;

                    if (scrollTop + 300 >= scrollHeight && !this.loading && this.nextPage) {
                        this.loading = true;

                        const response = await fetch(this.nextPage, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            document.getElementById('posts-list').insertAdjacentHTML('beforeend', data.html);
                            Alpine.initTree(document.getElementById('posts-list'));
                            this.nextPage = data.nextPage;
                        }

                        this.loading = false;
                    }
                }
            }));
        });
    </script>
@endsection