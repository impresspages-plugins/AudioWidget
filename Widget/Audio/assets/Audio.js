/**
 * @package ImpressPages
 *
 */


var IpWidget_Audio = function () {
    "use strict";

    var $this = this;

    this.widgetObject = null;
    this.confirmButton = null;
    this.popup = null;
    this.data = {};
    this.textarea = null;

    this.init = function (widgetObject, data) {

        this.widgetObject = widgetObject;
        this.data = data;

        var container = this.widgetObject.find('.ipsContainer');

        if (this.data.html) { // TODOXX check if not safe mode #129
            container.html(this.data.html);
        }

        var context = this; // set this so $.proxy would work below

        var $widgetOverlay = $('<div></div>')
            .css('position', 'absolute')
            .css('z-index', 5)
            .width(this.widgetObject.width())
            .height(this.widgetObject.height());
        this.widgetObject.prepend($widgetOverlay);
        $widgetOverlay.on('click', $.proxy(openPopup, context));

    };

    this.onAdd = function () {
        $.proxy(openPopup, this)();
    };


    var openPopup = function () {
        var context = this;
        this.popup = $('#ipWidgetAudioPopup');
        this.confirmButton = this.popup.find('.ipsConfirm');
        this.soundcloudUrl = this.popup.find('input[name=soundcloudUrl]');
        this.source = this.popup.find('select[name=source]');

        if (this.data.soundcloudUrl) {
            this.soundcloudUrl.val(this.data.soundcloudUrl);
        } else {
            this.soundcloudUrl.val(''); // cleanup value if it was set before
        }

        this.source.find('[value=soundcloud]').attr('selected', 'selected');

        this.popup.modal(); // open modal popup

        this.confirmButton.off(); // ensure we will not bind second time
        this.confirmButton.on('click', $.proxy(save, this));

        $this.popup.find('.ipsAudioFileList').html(''); // Delete file list
//            this.popup.append(file); // TODO

        if (typeof this.data.audioFiles != 'undefined')
            $.each(this.data.audioFiles, function (key, value) {

                var cloned = $(".ipsAudioFileTemplate").clone().show();
                cloned.removeClass('ipsAudioFileTemplate');
                cloned.find('source').attr('src', value.fileUrl);
                cloned.find('label').html(value.fileName);
                cloned.appendTo('.ipsAudioFileList');

            });


        this.popup.find(".ipsAudioFileList").sortable({
            handle: '.ipsAudioFileMove',
            cancel: false
        });


        this.popup.find('.ipsAudioFileRemove').off().on('click', function () {
                $(this).closest('div').parent().remove();
            }
        );

        this.popup.find('.ipsUploadAudioFile').off().on('click', function (e) {
            e.preventDefault();
            ipBrowseFile($.proxy(addFilesToPopup, this), {preview: 'list'});
        });

        this.popup.find('select[name=source]').off().on('change', function(){
            displaySelectedDialog(this.value);
        });

        //this.popup.find('select[name=source]').val('soundcloud');

        displaySelectedDialog(this.data.source);

    };

    var save = function () {

        var entry;

        var audioFiles = [];

        var a = this.popup.find('.ipsAudioFileList source');

        for (var i = 0; i < a.length; i++) {

            entry = a[i];

            var fileEntry = {
                fileUrl: $(entry).attr('src'),
                fileName: $(entry).parent().parent().find('._label').html()
            };

            audioFiles.push(fileEntry);

        }

        var data = {
            soundcloudUrl: this.soundcloudUrl.val(),
            source: this.source.val(),
            audioFiles: audioFiles
        };

        this.widgetObject.save(data, 1); // save and reload widget
        this.popup.modal('hide');
    };

    function displaySelectedDialog(strDialog) {
        if (strDialog != 'file' && strDialog != 'soundcloud') {
            strDialog = 'file';
        }

        if (strDialog == 'file'){
            $('#ipsAudioFile').show();
            $('.ipsAudioFileList').show();
            $('#ipsAudioSoundcloud').hide();
            $('.ipsUploadAudioFile').show();
        } else {
            $('#ipsAudioFile').hide();
            $('.ipsAudioFileList').hide();
            $('#ipsAudioSoundcloud').show();
            $('.ipsUploadAudioFile').hide();
        }

        $this.popup.find('select[name=source]').val(strDialog);
    }

    function addFilesToPopup(files) {


        $.each(files, function (key, value) {

            var cloned = $(".ipsAudioFileTemplate").clone().show();
            cloned.removeClass('ipsAudioFileTemplate');
            cloned.find('source').attr('src', value.originalUrl);
            cloned.find('label').html(value.fileName);
            cloned.appendTo('.ipsAudioFileList');
        });


        $this.popup.find('.ipsAudioFileRemove').off().on('click', function () {
                $(this).closest('div').parent().remove();
            }
        );

    }

};


