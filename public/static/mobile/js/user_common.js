
function layerPage(paginator) {
	layui.use('laypage', function() {
		var laypage = layui.laypage;
		laypage.render({
			elem : 'laypage',
			theme: '#ff8d00',
			groups: 2,
			prev: false,
			curr : paginator.page || 1,
			count : paginator.count,
			limit : paginator.pageSize || 1,
			jump : function(obj, first) {
				if (!first) {
					getList(obj.curr);
				}
			}
		});
	});
}
