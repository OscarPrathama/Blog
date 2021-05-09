(function($){

    // bulks admin checkbox
    var $table = $('.admin-post-table, .admin-inbox-table');
    var $chkbxs = $('.admin-post-table .ch-bulks, .admin-inbox-table .ch-bulks');
    var last_checked = null;
    $chkbxs.on('click', function(e){
        if (!last_checked){
            last_checked = this;
            return;
        }
        if(e.shiftKey){
            var start = $chkbxs.index(this);
            var end = $chkbxs.index(last_checked);
            $chkbxs.slice(Math.min(start, end), Math.max(start, end)+1).prop('checked', last_checked.checked);
        }
        last_checked = this;
    });

    // create post page
    tinymce.init({
        selector: 'textarea#postContent',
        height: 400,
        // menubar: true,
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
    let remove_img_preview_post_create = $('.post-create a#removeImgPreview');
    $('[name=post_img_feature]').on('change', function(){
        readURL(this);
        remove_img_preview.css('display', 'block');
    });

    remove_img_preview_post_create.on('click', function(){
        if(confirm('Are you sure ?') == true){
            img_preview.attr('src', '');
            img_preview.attr('alt', '');
            $('[name=post_img_feature]').val('');
            imgSrcCheck();
        }
    });

    function imgSrcCheck(){
        if( img_preview.attr('src').length != '' ){
            remove_img_preview.css('display', 'block');
        }else{
            remove_img_preview.css('display', 'none');
        }
    }

    if( img_preview.length != 0 ){
        imgSrcCheck()
    }

    function readURL(input){
        if (input.files && input.files[0]){
            let reader = new FileReader();
            reader.onload = function(e){
                img_preview.attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


    // admin menu form custom field
    var MaxInputs               = 2; // row menu limit
    var InputsWrapper           = $("#InputsWrapper");
    var AddButton               = $("#AddField");
    var InputsWrapperLength     = InputsWrapper.length;

    $(AddButton).on('click', function(){

        var FieldMenuWrapperLength  = $('.field-menu-wrapper').length;
        FieldMenuWrapperLength++;

        if (InputsWrapperLength >= 1) {
            $(InputsWrapper).append(`
                <div id="field_`+FieldMenuWrapperLength+`" class="field-menu-wrapper mt-3">
                    <input type="text" name="menus[row-`+FieldMenuWrapperLength+`][menu_title]" value="" placeholder="Menu Title" class="my-form-control">
                    <input type="text" name="menus[row-`+FieldMenuWrapperLength+`][menu_link]" value="" placeholder="Menu Link" class="my-form-control" size="30">
                    <a href="javascript:void(0)" class="removeclass">Remove</a>
                </div>
            `);
            InputsWrapperLength++;
            $(AddButton).show();

            // delete add button
            if(InputsWrapperLength === 3){
                $(AddButton).hide();
            }
            return false
        }
    });

    if ($('.field-menu-wrapper').length > 1) {
        $('.field-menu-wrapper').not(':first').append(`
            <a href="javascript:void(0)" class="removeclass">Remove</a>
        `);
        $('.removeclass').on('click', function(){
            $(this).parent().remove();
        });
    }

    $('body').on('click', '.removeclass', function(){
        if (InputsWrapperLength > 1) {
            var $this = $(this);
            $this.parent().remove();
            InputsWrapperLength--;
            $(AddButton).show();
        }
        return false;
    });


    // jquery plugin
    $.fn.textRight = function(){
        this.css('text-align', 'right');
        return this;
    }

    $('.admin-menu-action-column').textRight();

    $.fn.notFirstEl = function(class_name){
        this.not(':first').addClass(class_name);
    }

    $('div#InputsWrapper .field-menu-wrapper').notFirstEl('mt-3');

}(jQuery));



