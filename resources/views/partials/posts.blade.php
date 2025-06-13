@foreach ($posts as $post)
    @can('view', $post)
        <x-post :post="$post" />
    @endcan
@endforeach