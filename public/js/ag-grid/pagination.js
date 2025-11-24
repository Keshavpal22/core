/* public/js/ag-grid/pagination.js */

window.AGGridPagination = {

    update(gridId) {
        const grid = AGGridConfig.grids[gridId];
        if (!grid.api) return;

        const current = grid.api.paginationGetCurrentPage() + 1;
        const total = grid.api.paginationGetTotalPages();
        const totalRows = grid.api.getDisplayedRowCount();
        const pageSize = grid.api.paginationGetPageSize();

        const start = (current - 1) * pageSize + 1;
        const end = Math.min(start + pageSize - 1, totalRows);

        document.getElementById(gridId + "_range").innerHTML = `${start} to ${end} of ${totalRows}`;
        document.getElementById(gridId + "_page").innerHTML = `Page ${current} of ${total}`;
    },

    first(id) { AGGridConfig.grids[id].api.paginationGoToFirstPage(); this.update(id); },
    prev(id)  { AGGridConfig.grids[id].api.paginationGoToPreviousPage(); this.update(id); },
    next(id)  { AGGridConfig.grids[id].api.paginationGoToNextPage(); this.update(id); },
    last(id)  { AGGridConfig.grids[id].api.paginationGoToLastPage(); this.update(id); }
};
