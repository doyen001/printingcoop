function refreshGrid(id) {
	var grid = $(`#${id}`).data('kendoGrid');
	grid.dataSource.page(grid.dataSource.page());
}
