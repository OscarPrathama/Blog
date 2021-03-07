$(function(){
    
    // books admin checkbox
    var $table = $('.admin-post-table');
    var $chkbxs = $('.admin-post-table .ch-bulks');
    var last_checked = null;
    $chkbxs.on('click', function(e){
        if (!last_checked){
            last_checked = this;
            return;
        }
        if(e.shiftKey){
            console.log($chkbxs.index(last_checked));
            var start = $chkbxs.index(this);
            var end = $chkbxs.index(last_checked);
            $chkbxs.slice(Math.min(start, end), Math.max(start, end)+1).prop('checked', last_checked.checked);
        }
        last_checked = this;
    });

});