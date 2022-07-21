/**
 * Override the default yii confirm dialog. This function is
 * called by yii when a confirmation is requested.
 *
 * @param message the message to display
 * @param okCallback triggered when confirmation is true
 * @param cancelCallback callback triggered when cancelled
 */
yii.confirm = function (message, okCallback, cancelCallback) {
    swal({
        title: message,
        type: 'warning',
        showCancelButton: true,
        closeOnConfirm: true,
        allowOutsideClick: true
    }, okCallback);
};

function desactivarNotificaciones(user_id){
	console.log("Usuario -> " + user_id);
	$.ajax({
	    url: "notificaciones/notificaciones-leidas?user_id=" + user_id
	}).done( function() {
	    console.log( 'Success!!' );
	    $('#numeroNotificaciones').text("0");
	}).fail( function() {
	    console.log( 'Error!!' );
	});
}