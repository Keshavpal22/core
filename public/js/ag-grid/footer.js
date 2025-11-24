/* public/js/ag-grid/footer.js */

window.AGGridFooter = {

    create(gridId) {
        const grid = AGGridConfig.grids[gridId];
        const footer = document.getElementById(gridId + "_footer");

        footer.innerHTML = ""; // Clear

        const container = document.createElement("div");
        container.style.display = "flex";
        container.style.width = "fit-content";

        grid.columnDefs.forEach(col => {
            const cell = document.createElement("div");
            cell.style.border = "1px solid #dadada";
            cell.style.height = "28px";
            cell.style.padding = "4px 6px";
            cell.style.display = "flex";
            cell.style.alignItems = "center";
            cell.style.minWidth = (col.width || 120) + "px";

            // numeric columns
            if (["total_copies","available_copies","publication_year"].includes(col.field)) {
                cell.innerHTML = `
                    <select onchange="AGGridAggregate.calc('${gridId}','${col.field}',this.value)"
                            style="height:26px; margin-right:4px;">
                        <option value="">--</option>
                        <option value="sum">SUM</option>
                        <option value="avg">AVG</option>
                        <option value="min">MIN</option>
                        <option value="max">MAX</option>
                        <option value="count">COUNT</option>
                        <option value="product">PRODUCT</option>
                    </select>
                    <span id="${gridId}_${col.field}_value"></span>
                `;
            } else {
                cell.innerHTML = `<span></span>`;
            }

            container.appendChild(cell);
        });

        footer.appendChild(container);
    },

    update(gridId) {
        const grid = AGGridConfig.grids[gridId];

        ["total_copies", "available_copies", "publication_year"].forEach(field =>
            AGGridAggregate.calc(gridId, field)
        );
    }
};
