$(function(){

    // bulks admin checkbox
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

    // create post page
    tinymce.init({
        selector: 'textarea#postContent',
        height: 500,
        menubar: true,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
    });

    // image for create & edit post
    let img_preview = $('#imgPreview');
    let remove_img_preview = $('a#removeImgPreview');
    $('[name=post_img_feature]').on('change', function(){
        readURL(this);
        remove_img_preview.css('display', 'block');
    });

    remove_img_preview.on('click', function(){
        img_preview.attr('src', '');
        $('[name=post_img_feature]').val('');
        imgSrcCheck();
    });

    function imgSrcCheck(){
        if( img_preview.attr('src').length != '' ){
            remove_img_preview.css('display', 'block');
        }else{
            remove_img_preview.css('display', 'none');
        }
    }imgSrcCheck();

    function readURL(input){
        if (input.files && input.files[0]){
            let reader = new FileReader();
            reader.onload = function(e){
                img_preview.attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

});
