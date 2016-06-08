$(function() {
    function openLazyNode(event, nodes, node, hasChildren) {
        if (hasChildren) {
            return false;
        }
    }
    var easyTree = $('#employees_treeview').easytree({
        openLazyNode: openLazyNode,
        dataUrl: '/employee/treeview'
    });
});