var init_event_select_cover = function() {
    var $input = $('.js_input_event_change_cover'),
        $img_box_preview = $('.js_event_cover_preview'),
        $img_box_crop = $('.js_image_crop_box'),
        $popup = $('#popup_event_crop_image'),
        $ghost = $('#image_ghost'),
        _file, FAImg, FAImgGhost,
        $cropper = $('._cropper'),
        _dnr = false,
        size = $img_box_preview.data('mixsize'),
        cW=size, cH=size, cX=0, cY=0, cK = 1,
        $btnApply = $('._editAvatarApply'),
        event_id = $('#event_id').val(),
        file_name = 'EventForm3[cover]',
        uploadOpts = {
            url: $img_box_preview.data('url')
        };

    var _onSelectFile = function(evt) {
        _file = FileAPI.getFiles(evt)[0];
        if (_file) {
            $('.js_btn_show_event_cover_resize').click();
            _createPreview();
        }
        /*var files = FileAPI.getFiles(evt);
        FileAPI.filterFiles(files, function(file, info *//**Object*//*) {
            if (/^image/.test(file.type)) {
                _file = file;
                _createPreview();
            }
        }, function() {});*/
    };

    $btnApply.click(function() {
        _uploadFile();
    });

    var _createPreview = function(file) {
        var w = parseInt($img_box_crop.width()),
            h = parseInt($img_box_crop.height());

        FAImgGhost = FileAPI.Image(_file);
        FAImg = FAImgGhost.clone();

        FAImgGhost.get(function(err, image) {
            if (!err) {
                $ghost.html(image);
            }
        });

        FAImg
            .resize(w, h, 'max')
            .get(function(err, image) {
                if (!err) {
                    $($img_box_crop).find('canvas').remove();
                    $img_box_crop.prepend(image);
                    _calcCropSizes();
                }
            });
    };

    var _calcCropSizes = function() {
        var $canvas, $origin,
            wCrop, hCrop, wOrig, hOrig,
            minSizeCropper;
        $canvas = $($img_box_crop).find('canvas');
        $origin = $('canvas', $ghost);
        wCrop = $canvas.attr('width');
        hCrop = $canvas.attr('height');
        wOrig = $origin.width();
        hOrig = $origin.height();

        cK = wOrig / wCrop;
        minSizeCropper = parseInt(size / cK);
        if (minSizeCropper < size) {
            minSizeCropper = size;
        }
        if ((wOrig < size) || (hOrig < size)) {
            $btnApply.attr('disabled', 'disabled');
            alert($img_box_preview.data('errorsize'));
        } else {
            $btnApply.removeAttr('disabled');
            $img_box_crop.css({width:wCrop, height:hCrop});
            _showCropper(minSizeCropper, minSizeCropper, wCrop, hCrop);
        }
    }

    var _showCropper = function(minW, minH, maxW, maxH) {
        $cropper.show();
        if (_dnr) {
            $cropper.draggable('destroy');
            $cropper.resizable('destroy');
        }
        $cropper.draggable({
            containment:'parent',
            stop: function(event, ui) {
                cX = ui.position.left;
                cY = ui.position.top;
            }
        });
        $cropper.resizable({
            minWidth:minW, minHeight:minH, maxWidth:maxW, maxHeight:maxH, aspectRatio:true, containment:'parent',
            stop:function(event, ui) {
                cW = ui.size.width;
                cH = ui.size.height;
            }
        });
        _dnr = true;
        $cropper.css({left:0,top:0,width:size,height:size});
    }


    var _uploadFile = function() {
        var x, y, w, h;
        x = parseInt(cX * cK);
        y = parseInt(cY * cK);
        w = parseInt(cW * cK);
        h = parseInt(cH * cK);

        var token_name = $('meta[name="csrf-param"]').attr('content');
        var token = $('meta[name="csrf-token"]').attr('content');

        _file = FileAPI.Image(_file).crop(x, y, w, h).resize(size, size);

        var opts = FileAPI.extend(uploadOpts, {
            files: {},
            data: {event_id:event_id},
            upload: function() {
                $img_box_crop.addClass('loading');
            },
            complete: function(err, xhr) {
                $img_box_crop.removeClass('loading');
                if (err) {
                    alert('Oops! Server error.');
                } else {
                    _file.get(function(err, image) {
                        if (!err) {
                            $img_box_preview.html('<img src="'+image.toDataURL()+'" />');
                            $ghost.html('');
                        }
                    });
                    $popup.modal('hide');
                }
            }
        });

        opts.files[file_name] = _file;
        opts.data[token_name] = token;
        FileAPI.upload(opts);
    };

    $input.on('change', _onSelectFile);
}

$(document).ready(function() {
    init_event_select_cover();
});