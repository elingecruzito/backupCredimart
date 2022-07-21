$('#g02_id').empty();
$('#g04_semana').empty();

var txtLoad = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';

$('#g01_id').change(function(){
	//$('#table-refresh').append(text);
	//console.log($('#g01_id').val());
	$('#g02_id').empty();

	if($('#g01_id').val() != ''){

		$.ajax({
		    type: 'GET',
		    url: 'clienteslist/' + $('#g01_id').val()
		}).then(function (data) {

			data = JSON.parse(data);

		    for(i=0; i < data.length; i++){
		    	$('#g02_id').append(new Option(data[i].g02_nombre, data[i].g02_id, true, true)).trigger('change');
		    }

		});


	}
});


$('#g02_id').change(function(){
	
	$('#g04_semana').empty();
	$('#g04_cantidad').val("");
	
	if($('#g02_id').val() != ''){

		$.ajax({
		    type: 'GET',
		    url: 'semanaspagosclientes/' + $('#g02_id').val()
		}).then(function (data) {

			data = JSON.parse(data);

		    for(i=0; i < data.length; i++){
		    	$('#g04_semana').append(new Option(data[i].value, data[i].index, true, true)).trigger('change');
		    }

		   
		    $('#g04_semana').val(data[0].index).trigger("change");

		    $.ajax({
			    type: 'GET',
			    url: 'abono/' + $('#g02_id').val()
			}).then(function (data) {

				$('#g04_cantidad').val(data);
			   
			});
		})
		.fail(function (jqXHR, exception) {
			swal({
			    title: 'Error',
			    type: 'error',
			    text: "El cliente seleccionado no tiene un prestamo activo.",
			});

			$('#g04_semana').empty();
			$('#g04_cantidad').val("");
		});

	}
});

