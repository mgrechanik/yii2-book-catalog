jQuery( document ).ready(function() {
    let $button = $('#deleteOldImageButton');
    $button.click(function(){
        let $this = $(this);
        $this.hide();
        $this.parent().find('img').hide();
        $('#bookupdateform-needdeleteoldimage').val(1);
    })

});

