$(function() {
    // preventing page from redirecting
    $("html").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $("#file-drop").text("Faites glisser ici");
    });

    $("html").on("drop", function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Drag enter
    $('.upload-area').on('dragenter', function(e) {
        e.stopPropagation();
        e.preventDefault();
        $("#file-drop").text("Laissez tomber");
    });

    // Drag over
    $('.upload-area').on('dragover', function(e) {
        e.stopPropagation();
        e.preventDefault();
        $("#file-drop").text("Laissez tomber");
    });

    // Drop
    $('.upload-area').on('drop', function(e) {
        e.stopPropagation();
        e.preventDefault();

        $('#file').prop('disabled', true);
        $(".file-btn").hide();
        $("#file-drop").text("téléchargement du fichier, veuillez patienter...");
        var file = e.originalEvent.dataTransfer.files;
        var fd = new FormData();
        fd.append('file', file[0]);
        uploadData(fd);
    });

    // Open file selector on div click
    $("#uploadfile").click(function() {
        $("#file").click();
    });

    // file selected
    $("#file").change(function() {
        var product_id = $("#product_id").val();
        $('#file').prop('disabled', true);
        $(".file-btn").hide();
        $("#file-drop").text("téléchargement du fichier, veuillez patienter...");
        var fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file', files);
        fd.append('product_id', product_id);
        uploadData(fd);
    });
});

// Sending AJAX request and upload file
function uploadData(formdata) {
    $('#loader-img').show();
    var site_url = document.getElementById("site_url_foot").value;

    $.ajax({
        url: site_url + 'Products/uploadImage',
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            $('#loader-img').hide();
            addThumbnail(response);
        }
    });
}
// Added thumbnail
function addThumbnail(data) {
    //$("#uploadfile #file-drop").remove();
    $('#file').prop('disabled', false);
    $(".file-btn").show();
    $("#file-drop").text("Glisser-déposer des fichiers");
    var len = $("#uploadfile div.thumbnail").length;
    var num = Number(len);
    num = num + 1;
    var name = data.name;
    var size = convertSize(data.size);
    var src = data.src;
    var skey = data.skey;
    var product_id = data.product_id;
    var error = data.error;
    var error_msg = data.error_msg;

    if (error == '1') {
        alert(error_msg);
    }

    // Creating an thumbnail
    /*$("#uploadfile").append('<div id="thumbnail_'+num+'" class="thumbnail"></div>');
    $("#thumbnail_"+num).append('<img src="'+src+'" width="100%" height="78%">');
    $("#thumbnail_"+num).append('<span class="size">'+size+'<span>');*/
    //alert(src);

    //var html_str='<div class="uploaded-file-single"><div class="uploaded-file-single-inner"><div class="uploaded-file-img" style="background-image: url('+src+')"></div><div class="uploaded-file-info"><div class="row align-items-center"><div class="col-md-7"><div class="uploaded-file-name"><span>'+name+'</span></div></div><div class="col-md-5"><div class="upload-action-btn"><button type="button" onclick="delete_image()">Update Note</button><button type="submit" title="Delete"><i class="las la-trash"></i></button></div></div></div><div class="upload-field"><textarea type="text"></textarea></div></div></div></div>';

    $("#upload-file-data").append(data.html);
}
// Bytes conversion
function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}