/* public/js/ag-grid/aggregate.js */

window.AGGridAggregate = {

    calc(gridId, field, type = null) {
        const grid = AGGridConfig.grids[gridId];
        const api = grid.api;

        // User-selected or previously selected
        const activeSelect = document.querySelector(`#${gridId}_footer select[onchange*="${field}"]`);
        if (type === null) type = activeSelect ? activeSelect.value : "";

        if (!type) {
            document.getElementById(`${gridId}_${field}_value`).innerHTML = "";
            return;
        }

        const values = [];
        api.forEachNodeAfterFilterAndSort(node => {
            const v = Number(node.data[field]);
            if (!isNaN(v)) values.push(v);
        });

        let result = "";

        switch (type) {
            case "sum": result = values.reduce((a,b) => a+b, 0); break;
            case "avg": result = (values.reduce((a,b)=>a+b,0) / values.length || 0).toFixed(2); break;
            case "min": result = Math.min(...values); break;
            case "max": result = Math.max(...values); break;
            case "count": result = values.length; break;
            case "product": result = values.reduce((a,b)=>a*b, 1); break;
        }

        document.getElementById(`${gridId}_${field}_value`).innerHTML = result;
    }
};
