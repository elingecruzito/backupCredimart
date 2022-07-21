
var calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
	plugins: [ 'interaction', 'dayGrid' ],
	//defaultDate: '2019-04-12',
	editable: false,
	eventLimit: true, // allow "more" link when too many events
	events: [

	]
});

calendar.render();