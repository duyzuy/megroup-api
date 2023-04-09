(function($) {

    $(document).ready(function() {



        //file download for post type download
        $('.btn-upload-file').click(function(e) {
            e.preventDefault()
            var inputhd = $(this).parent('.pyre_field').find('input:hidden');
            var inputName = $(this).parent('.pyre_field').find('input:text');
            media_uploader = wp.media({
    
                title: "Choose file",
                library: {
                    order: 'DESC',
            
                    // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo', 'id', 'post__in', 'menuOrder' ]
                    orderby: 'date',
            
                    // mime type. e.g. 'image', 'image/jpeg'
                    type: 'application/pdf',
            
                    // Searches the attachment title.
                    search: null,
            
                    // Includes media only uploaded to the specified post (ID)
                    uploadedTo: null // wp.media.view.settings.post.id (for current post ID)
                },
                multiple: false
            });
    
            media_uploader.on("select", function() {
    
               
                var file = media_uploader.state().get("selection").models
                
       
                var fileId = file[0].attributes.id;
                var fileName = file[0].attributes.filename;
                var fileUrl = file[0].attributes.url;
              
                inputhd.val(fileId);
                inputName.val(fileUrl);
            });
    
            media_uploader.open();
        });

        
        //slide select onchange function
        $('.pyre_slider_type input').change(function(){
              
            if($(this).val() == 'image'){
             $('.pyre_content_image_banner').addClass('active');
             $('.pyre_content_video_banner').removeClass('active');
            }else{
             $('.pyre_content_image_banner').removeClass('active');
             $('.pyre_content_video_banner').addClass('active');
            }
        })
        var btnUploads =  document.getElementsByClassName('btn-upload-banner');
    
        

        $('.btn-upload-banner').each(function(){
            $(this).on('click', function(e) {
                e.preventDefault()
                var inputhd = $(this).parent('.pyre_field').find('input:hidden');
                var inputSrc = $(this).parent('.pyre_field').find('.thumbnail_url');
                var thumbnail = $(this).parent('.pyre_field').find('.pyre_thumbnail');
                var image = $('<img>');
                media_uploader = wp.media({
        
                    title: "Choose file",
                    library: {
                        order: 'DESC',
                
                        // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo', 'id', 'post__in', 'menuOrder' ]
                        orderby: 'date',
                
                        // mime type. e.g. 'image', 'image/jpeg'
                        type: 'image',
                
                        // Searches the attachment title.
                        search: null,
                
                        // Includes media only uploaded to the specified post (ID)
                        uploadedTo: null // wp.media.view.settings.post.id (for current post ID)
                    },
                    multiple: false
                });
        
                media_uploader.on("select", function() {
        
                    
                    var file = media_uploader.state().get("selection").models
                    
            
                    var fileId = file[0].attributes.id;
                    var fileName = file[0].attributes.filename;
                    var fileUrl = file[0].attributes.url;
                
                    image.attr('src', fileUrl);
                    thumbnail.html('');
                    thumbnail.html(image)
                    inputhd.val(fileId);
                    inputSrc.val(fileUrl);
                   
                });
        
                media_uploader.open();
            })
        });

        
            $('.btn-remove-banner').on('click', function(e) {
                e.preventDefault();

                var inputHd = $(this).parent('.pyre_field').find('input:hidden');
                
                    inputHd.val('');

           
                $(this).parent('.pyre_field').find('.pyre_thumbnail').children('img').remove();
                $(this).parent('.pyre_field').find('.pyre_thumbnail').html('<span>No image</span>');


            });
        

        //multi media chose for product post type gallery


        $('.btn-upload-gallery-multi').click(function(e) {
            e.preventDefault()
            var inputhd = $(this).parent('.pyre_field').find('input:hidden');
            var gallerys = $(this).parent('.pyre_field').find('.gallery-thumb');
            media_uploader = wp.media({

                title: "Choose Gallery",
                library: {
                    order: 'DESC',
            
                    // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo', 'id', 'post__in', 'menuOrder' ]
                    orderby: 'date',
            
                    // mime type. e.g. 'image', 'image/jpeg'
                    type: 'image',
            
                    // Searches the attachment title.
                    search: null,
            
                    // Includes media only uploaded to the specified post (ID)
                    uploadedTo: null // wp.media.view.settings.post.id (for current post ID)
                },
                multiple: true
            });

            media_uploader.on("select", function() {

                var length = media_uploader.state().get("selection").length;
                var images = media_uploader.state().get("selection").models

                if (inputhd.val() == '') {
                    var idargs = [];
                    for (var iii = 0; iii < length; iii++) {
                        var image_url = images[iii].changed.url;
                        gallerys.append('<li class="thumb-image thumb-image-' + iii + '"><image width="100" height="100" src="' + image_url + '" class="img-responsive"></li>').attr('src', image_url);
                        var image_id = images[iii].attributes.id;
                        idargs.push(image_id);
                        var image_caption = images[iii].changed.caption;
                        var image_title = images[iii].changed.title;

                    }
                } else {
                    idargs = JSON.parse("[" + inputhd.val() + "]");
                    for (var iii = 0; iii < length; iii++) {
                        var image_id = images[iii].attributes.id;
                        var image_url = images[iii].changed.url;
                        gallerys.append('<li class="thumb-image thumb-image-' + iii + '"><image width="100" height="100" src="' + image_url + '" class="img-responsive"></li>').attr('src', image_url);
                        if (!inArray(image_id, idargs)) {
                            idargs.push(image_id);
                        }
                        var image_caption = images[iii].changed.caption;
                        var image_title = images[iii].changed.title;

                    }
                    console.log(idargs);
                }
                inputhd.val(idargs);
            });

            media_uploader.open();

        });

        $('.js_btn-remove-img').each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();

                var inputHd = $(this).parent('li').parent('.gallery-thumb').parent('.pyre_field').find('input:hidden');
                var imageIds = inputHd.val();
                var imageIdsArray = JSON.parse("[" + imageIds + "]");
                var id = $(this).data('id');
                for (var i = 0; i < imageIdsArray.length; i++) {
                    remove(imageIdsArray, id);
                    inputHd.val(imageIdsArray);

                }
                $(this).parent('li').remove();


            });
        });

        $('.btn-remove-gallery').each(function() {

            $(this).on('click', function(e) {
                e.preventDefault();
                $('#project-gallery-value').val('');
                $('ul.gallery-thumb').empty();
                $('.btn-remove-gallery').remove();
                $('input[name="pyre_gallery_project_recent"]').val('');
            });
        });



        //Slider banner upload for slider post type
        
     




    })

    function inArray(target, array) {

        /* Caching array.length doesn't increase the performance of the for loop on V8 (and probably on most of other major engines) */

        for (var i = 0; i < array.length; i++) {
            if (array[i] === target) {
                return true;
            }
        }

        return false;
    }

    function remove(arr, target) {
        var found = arr.indexOf(target);
        while (found !== -1) {
            arr.splice(found, 1);
            found = arr.indexOf(target);
        }
    }



    //download click link

    jQuery('.btn-uploader').on('click', function(e) {
        e.preventDefault()
        var inputhd = jQuery(this).parent('.pyre_field').find('input:hidden');
    
        media_uploader = wp.media({

            title: "Choose file",
        
            multiple: false
        });

        media_uploader.on("select", function() {

            
            var file = media_uploader.state().get("selection").models
            
    
            var fileId = file[0].attributes.id;
            var fileName = file[0].attributes.filename;
            var fileUrl = file[0].attributes.url;
        
            inputhd.val(fileId);
            inputName.val(fileUrl);
        });

        media_uploader.open();
    
});


})(jQuery);