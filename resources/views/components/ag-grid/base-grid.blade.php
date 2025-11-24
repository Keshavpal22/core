{{-- resources/views/components/ag-grid/base-grid.blade.php --}}
<div>

    {{-- Toolbar Slot (Optional) --}}
    <div class="mb-2">
        {{ $toolbar ?? '' }}
    </div>

    {{-- AG-Grid Container --}}
    <div id="{{ $gridId }}" class="ag-theme-alpine" style="width:100%; height: {{ $height ?? '520px' }};">
    </div>

    {{-- Custom Pagination --}}
    <div id="{{ $gridId }}_pagination"
         class="d-flex justify-content-end align-items-center mt-2 gap-2">

        <span id="{{ $gridId }}_range" class="me-3 fw-bold"></span>

        <button class="btn btn-light btn-sm" onclick="AGGridPagination.first('{{ $gridId }}')">⏮</button>
        <button class="btn btn-light btn-sm" onclick="AGGridPagination.prev('{{ $gridId }}')">◀</button>

        <span id="{{ $gridId }}_page" class="mx-2 fw-bold"></span>

        <button class="btn btn-light btn-sm" onclick="AGGridPagination.next('{{ $gridId }}')">▶</button>
        <button class="btn btn-light btn-sm" onclick="AGGridPagination.last('{{ $gridId }}')">⏭</button>
    </div>

    {{-- Sticky Footer --}}
    <div id="{{ $gridId }}_footer"
         class="ag-theme-alpine mt-2"
         style="width:100%; overflow-x:auto;">
    </div>

</div>

@push('script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    AGGridConfig.initGrid({
        gridId: "{{ $gridId }}",
        columnDefs: {!! json_encode($columns) !!},
        rowData: {!! json_encode($rows) !!},
        routes: {
            view: "{{ $viewRoute ?? '' }}",
            edit: "{{ $editRoute ?? '' }}"
        }
    });
});
</script>
@endpush
