 $(document).ready(function() {
    $("a.editme").colorbox({
            iframe:true,
            /*   data:{idchgmt:$(this).attr("data-idchgmt")},   */ 
                  transition:	"elastic",
            width:"70%",
            height:"70%",
              fastIframe:false,
            opacity:0.3
        
        });
        
        
        });
$(document).bind('dragover', function (e) {
    var dropZone = $('#dropzone'),
        timeout = window.dropZoneTimeout;
    if (!timeout) {
        dropZone.addClass('in');
    } else {
        clearTimeout(timeout);
    }
    var found = false,
      	node = e.target;
    do {
        if (node === dropZone[0]) {
       		found = true;
       		break;
       	}
       	node = node.parentNode;
    } while (node != null);
    if (found) {
        dropZone.addClass('hover');
    } else {
        dropZone.removeClass('hover');
    }
    window.dropZoneTimeout = setTimeout(function () {
        window.dropZoneTimeout = null;
        dropZone.removeClass('in hover');
    }, 2000);
});

 function remplirSelect (data) {
   var optionData = data;
          /*  optionData['fields']*/
     /*     $("#uploaded-files").append('<div style="display:block;border:1px dashed #CCCCCC;">' + optionData['fields']['name'] + '</div>');
      */    
 
} //Eof:: fucntion remplirSelect

    $(function () {
 $('#fileupload').fileupload({
      apc: true,
      filesContainer: $('table.files'),
       dropZone: $('#dropzone'),
       acceptFileTypes : /(\.|\/)(gif|jpe?g|png|crt|pem|key|p12|zip)$/i,
        uploadTemplateId: null,
    downloadTemplateId: null,
    dataType: 'json',
    url: Routing.generate('certificats_fileupload'),
    maxFileSize: 1000000, // 1000 kB

   uploadTemplate: function (o) {
        var rows = $();
        $.each(o.files, function (index, file) {
            var row = $('<tr class="template-upload fade">' +
                '<td><span class="preview"></span></td>' +
                '<td><p class="name"></p>' +
                (file.error ? '<div class="error"><strong></strong></div>' : '') +
                '</td>' +
                '<td><p class="size"></p>' +
                (o.files.error ? '' : '<div class="progress"></div>') +
                '</td>' +
                '<td>' +
                (!o.files.error && !index && !o.options.autoUpload ?
                    '<button class="start btn btn-primary"><i class="icon-upload icon-black"></i> Start</button>' : '') +
                (!index ? '<button class="cancel btn btn-danger""><i class="icon-upload icon-black"></i> Cancel</button>' : '') +
                '</td>' +
                '</tr>');
            row.find('.name').text(file.name);
            row.find('.size').text(o.formatFileSize(file.size));
            if (file.error) {
                row.find('.error').text(file.error);
            }
            rows = rows.add(row);
        });
        return rows;
    },
    downloadTemplate: function (o) {
        var rows = $();
        $.each(o.files, function (index, file) {
            var row = $('<tr class="template-download fade">' +
                '<td><span class="preview"></span></td>' +
                '<td><p class="name"></p>' +
                (file.error ? '<div class="error"></div>' : '') +
                '</td>' +
                '<td><span class="size"></span></td>' +
                '<td><button class="delete">Delete</button></td>' +
                '</tr>');
            row.find('.size').text(o.formatFileSize(file.size));
            if (file.error) {
                row.find('.name').text(file.name);
                row.find('.error').text(file.error);
            } else {
                row.find('.name').append($('<a></a>').text(file.name));
                if (file.thumbnailUrl) {
                    row.find('.preview').append(
                        $('<a></a>').append(
                            $('<img>').prop('src', file.thumbnailUrl)
                        )
                    );
                }
                row.find('a')
                    .attr('data-gallery', '')
                    .prop('href', file.url);
                row.find('.delete')
                    .attr('data-type', file.delete_type)
                    .attr('data-url', file.delete_url);
            }
            rows = rows.add(row);
        });
        return rows;
    },
    done: function (e, reponse) {
       var data = reponse;
       /* $.each(data.result.files, function (index, file) {
             
           
            });*/
    }
     /* done: function (e, reponse) {
            var data = reponse;
           
            $.each(data.result.files, function (index, file) {
             
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }*/
   
      
    });
   
});

