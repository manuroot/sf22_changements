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
/*
window.tmpl = function (id) {
    if (id === 'template-upload') {
        return function (o) {
            return '<tr class="template-upload"><!-- ... --></tr>';
        };
    }
   };*/
  var progressElements = { };
    $(function () {
      var progressElements = { };
  /*      $('#fileupload').bind('fileuploadprogress', function (e, data) {
    // Log the current bitrate for this upload:
    console.log(data.bitrate);
});*/
 $('#fileupload').fileupload({
      apc: true,
      filesContainer: $('table.files'),
       dropZone: $('#dropzone'),
       acceptFileTypes : /(\.|\/)(gif|jpe?g|png|crt|pem|key|p12|zip)$/i,
    /* uploadTemplateId: null,
    downloadTemplateId: null,*/
    dataType: 'json',
     disableImageResize: /Android(?!.*Chrome)|Opera/
        .test(window.navigator && navigator.userAgent),
    imageMaxWidth: 800,
    imageMaxHeight: 800,
    imageCrop: true ,
    url: Routing.generate('certificats_fileupload'),
    maxFileSize: 10000000, // 10000 kB
// Callback for global upload progress events:


  progressall: function (e, data) {
                var $this = $(this),
                    progress = Math.floor(data.loaded / data.total * 100),
                    globalProgressNode = $this.find('.fileupload-progress'),
                    extendedProgressNode = globalProgressNode
                        .find('.progress-extended');
                if (extendedProgressNode.length) {
                    extendedProgressNode.html(
                        ($this.data('blueimp-fileupload') || $this.data('fileupload'))
                            ._renderExtendedProgress(data)
                    );
                }
                globalProgressNode
                    .find('.progress')
                    .attr('aria-valuenow', progress)
                    .children().first().css(
                        'width',
                        progress + '%'
                    );
            },
            
   progress: function (e, data) {
                var progress = Math.floor(data.loaded / data.total * 100);
                if (data.context) {
                    data.context.each(function () {
                        $(this).find('.progress')
                            .attr('aria-valuenow', progress)
                            .children().first().css(
                                'width',
                                progress + '%'
                            );
                    });
                }
            },
                    
                    /*<div class="progress ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">

    <div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%;"></div>

</div>*/
 uploadTemplate: function (o) {
     var rows = $();
        $.each(o.files, function (index, file) {
            var row = $('<tr class="template-upload fade">' +
                '<td><span class="preview"></span></td>' +
                '<td><p class="name"></p>' +
                (file.error ? '<div class="error"><strong></strong></div>' : '') +
                '</td>' +
                '<td><p class="size"></p>' +
               /*   (o.files.error ? '' :'<div class="progress ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' +
'<div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%;"></div>' +

'</div>') +*/
                  (o.files.error ? '' : '<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' ) +
                    '<div class="progress-bar progress-bar-success" style="width:0%;"></div>' + 
                    '</div>' +
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
               /* '<td class="preview"></td>' + */
                      '<td><span class="size"></span></td>' +
                '<td><button class="delete btn btn-primary">'+
                
                '<i class="icon-upload icon-black"></i> Clear</button>' +
                '  <input type="checkbox" name="delete" value="1">' + 
                '</td></tr>');
            row.find('.size').text(o.formatFileSize(file.size));
            if (file.error) {
                row.find('.name').text(file.name);
                row.find('.error').text(file.error);
            } else {
                row.find('.name').append($('<a></a>').text(file.name));
                if (file.thumbnailUrl) {
                    console.log("create thumbnail");
                    row.find('.preview').append(
                        $('<a></a>').append(
                            $('<img width="70px;">').prop('src', file.thumbnailUrl)
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
            row.append('');
            rows = rows.add(row);
        });
        return rows;
    }
           /* done: function (e, data) {
            data.context.text('Upload finished.');
        }*/
   /* done: function (e, reponse) {
       var data = reponse;
       $.each(data.result.files, function (index, file) {
             
           
            });
    }*/
 /* done: function (e, reponse) {
            var data = reponse;
           $('#progress .bar').css(
                    'width',
                    '0' + '%'
                );
            $.each(data.result.files, function (index, file) {
             
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }*/
   
      
    });
   /*
$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: Routing.generate('certificats_fileupload'),
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });
   

});*/
});

