@php
    $route_params = isset($route_params) ? $route_params : [];
    $is_default = isset($is_default) ? $is_default : false;
@endphp

<a href="{{ getSortableUrl($field, $default_ordering, $route_name, $route_params, $is_default) }}" class="app-table-heading-link">
    <span>{{ $label }}</span>

    @php $iconDirection = getSortableIconDirection($field, $default_ordering, $is_default); @endphp
    @if ($iconDirection)
        <i class="bi bi-caret-{{ $iconDirection }}-fill"></i>
    @endif
</a>