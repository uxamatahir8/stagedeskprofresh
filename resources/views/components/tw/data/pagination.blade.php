@props([
    'paginator' => null,
])

@if ($paginator && method_exists($paginator, 'links'))
    <div {{ $attributes->merge(['class' => 'mt-6']) }}>
        {{ $paginator->links() }}
    </div>
@endif
