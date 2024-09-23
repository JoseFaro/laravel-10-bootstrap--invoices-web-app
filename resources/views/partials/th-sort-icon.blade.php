@php
    $is_default = isset($is_default) ? $is_default : false;
@endphp

<div class="app-table-heading-icon">
    <span>{{ $label }}</span>

    @php $iconDirection = getSortableIconDirection($field, $default_ordering, $is_default); @endphp
    @if ($iconDirection)
        <i class="bi bi-caret-{{ $iconDirection }}-fill"></i>
    @endif
</div>