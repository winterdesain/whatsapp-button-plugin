jQuery(document).ready(function($) {
    var customUploader = wp.media({
        title: 'Pilih atau Unggah Logo',
        button: {
            text: 'Pilih Logo'
        },
        multiple: false
    });

    $('#upload_logo_button').click(function(e) {
        e.preventDefault();
        customUploader.open();
    });

    customUploader.on('select', function() {
        var attachment = customUploader.state().get('selection').first().toJSON();
        $('#logo_url').val(attachment.url);
    });
});
