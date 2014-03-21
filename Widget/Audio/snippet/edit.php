<div class="ip">
    <div id="ipWidgetAudioPopup" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo __('Audio widget settings', 'ipAdmin') ?></h4>
                </div>

                <div class="modal-body">
                    <?php echo $form->render(); ?>

                    <div class="ipsAudioFileList ui-sortable"></div>
                    <button type="button" class="btn btn-new btn btn-default ipAdminButton ipsUploadAudioFile"><?php echo __('Upload', 'ipAdmin') ?></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel', 'ipAdmin') ?></button>
                    <button type="button" class="btn btn-primary ipsConfirm"><?php echo __('Confirm', 'ipAdmin') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div draggable="true" class="form-group ipsAudioFile ipsAudioFileTemplate" style="display:none">
    <div class="input-group">
        <div class="input-group-btn">
            <button class="btn btn-default ipsAudioFileMove" type="button" title="Drag"><i class="fa fa-arrows"></i></button>
        </div>
        <audio controls style="width: 50%; height: 32px;"><source src="" type="audio/mpeg">Your browser does not support the audio element.</audio>
        <label class="_label"></label>
        <div class="input-group-btn">
            <button class="btn btn-danger ipsAudioFileRemove" type="button" title="Delete"><i class="fa fa-trash-o"></i></button>
        </div>
    </div>
</div>
