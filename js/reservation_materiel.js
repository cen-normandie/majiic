
//////////////////////////////////////////////CALENDAR
//Calendar
var calendarEl_ = document.getElementById('calendar');
var calendar_ = new FullCalendar.Calendar(calendarEl_, {
  timeZone:'UTC', 
  customButtons: {
    sw15: {
      text: '15min/30min',
      click: function() {
        if (calendar_.getOption('slotLabelInterval') == 30) {
            calendar_.setOption('slotDuration','00:15:00');
            calendar_.setOption('slotLabelInterval',15);
        } else {
            calendar_.setOption('slotDuration','00:30:00');
            calendar_.setOption('slotLabelInterval',30);
        }
      },
      buttonIcons:'alarm'
    }
  },
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: ''
  },
  footerToolbar:{
    left: '',
    center: 'sw15',
    right: ''
  },
  editable: true,
  droppable: true, // this allows things to be dropped onto the calendar
  drop: function(arg) {},
  forceEventDuration:true,
  initialView: 'timeGridWeek',
  locale: 'fr',
  slotDuration: '00:30:00',
  slotLabelInterval: 30,
  //slotDuration: '00:15:00',
  //slotLabelInterval: 15,
  scrollTime: '08:00:00',
  slotMinTime: '00:00:00', // Start time for the calendar
  slotMaxTime: '24:00:00', // End time for the calendar
  allDaySlot: false,
  editable: true,
  droppable: true, // this allows things to be dropped onto the calendar
  selectable: true,
  eventReceive: function (event) {
      get_uuid();
      add_event_properties(event.event);
      save_event(event.event);
      event.event.remove();
      calendar_.refetchEvents();
  },
  eventDrop: function (arg) {
      arg.event.e_start = arg.event.start;
      arg.event.e_end = arg.event.end;
      arg.event.e_uuid = arg.event._def.publicId;
      save_update_event_resized (arg.event);
      calendar_.refetchEvents();
  },
  eventClick: function(arg) {
    //calendar.render();
  },
  eventResize: function(arg) {
    arg.event.e_start = arg.event.start;
    arg.event.e_end = arg.event.end;
    arg.event.e_uuid = arg.event._def.publicId;
    save_update_event_resized (arg.event);
    calendar_.refetchEvents();
  },
  events: "php/ajax/reservation/load_events_calendar_reservation.js.php"
});
calendar_.render();

function load_matos() {
    
}