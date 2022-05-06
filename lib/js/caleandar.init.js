/* Caleandar Initialization */
fetch( wpvars.apiEndpoint )
  .then( res => { return res.json(); } )
  .then( data => {
    console.log( '👉 There are ' + data.length + ' events.' );
    var events = new Array();
    data.forEach( function( event, index ){
      events.push({
        'Date': new Date( event.date.year, event.date.month, event.date.day ),
        'Title': event.title,
        'Link': event.permalink
      });
    });
    console.log('🔔 events ', events );
    var settings = {};
    var calElement = document.getElementById('calendar');
    calElement.replaceChildren();
    caleandar(calElement, events, settings);
  })
  .catch( err => { console.error(error) } )