$('#prestamos-g03_abono').change(function() {
	var abono = $('#prestamos-g03_abono').val();
	$('#prestamos-g03_total').val(abono * 14);
});