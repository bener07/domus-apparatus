export class DataTableManager {
    constructor(tableId, options) {
        this.tableId = tableId;
        this.options = options;
        this.table = null;
    }

    init() {
        if ($.fn.dataTable.isDataTable(`#${this.tableId}`)) {
            $(`#${this.tableId}`).DataTable().clear().destroy();
        }

        this.table = $(`#${this.tableId}`).DataTable({
            paging: true,
            pageLength: 5,
            lengthChange: false,
            searching: false,
            info: false,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: (data, callback, settings) => {
                this.options.getData(data, (response) => {
                    callback({
                        draw: data.draw,
                        recordsTotal: response.total,
                        recordsFiltered: response.filtered,
                        data: response.data
                    });
                });
            },
            columns: this.options.columns,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
        });

        this.bindEvents();
    }

    bindEvents() {
        $(`#${this.tableId} tbody`).on('click', '.eliminar-btn', (event) => {
            const id = $(event.currentTarget).data('id');
            this.options.onDelete(id);
        });

        $(`#${this.tableId} tbody`).on('click', '.editar-btn', (event) => {
            const id = $(event.currentTarget).data('id');
            const data = $(event.currentTarget).data('product');
            this.options.onEdit(id, data);
        });
    }

    reload() {
        if (this.table) {
            this.table.ajax.reload();
        }
    }
}