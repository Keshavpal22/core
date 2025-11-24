/* public/js/ag-grid/config.js */

window.AGGridConfig = {

    grids: {},

    initGrid({ gridId, columnDefs, rowData, routes }) {

        const gridOptions = {
            columnDefs,
            rowData,
            animateRows: true,
            pagination: true,
            paginationPageSize: 10,
            rowSelection: 'multiple',
            rowDragManaged: true,
            suppressRowClickSelection: true,

            defaultColDef: {
                resizable: true,
                sortable: true,
                filter: true,
                floatingFilter: true
            },

            onModelUpdated: () => AGGridFooter.update(gridId),
            onPaginationChanged: () => AGGridPagination.update(gridId),
            onFilterChanged: () => AGGridFooter.update(gridId),
            onSortChanged: () => AGGridFooter.update(gridId),

            context: { routes }
        };

        const eGridDiv = document.getElementById(gridId);
        new agGrid.Grid(eGridDiv, gridOptions);

        this.grids[gridId] = gridOptions;

        // Create footer after grid renders
        setTimeout(() => AGGridFooter.create(gridId), 300);
    },
};
