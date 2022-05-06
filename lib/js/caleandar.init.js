/* Caleandar Initialization */
/*
var events = [
  {'Date': new Date(2022, 3, 29), 'Title': 'Doctor appointment at 3:25pm.'},
  {'Date': new Date(2022, 3, 20), 'Title': 'New Garfield movie comes out!', 'Link': 'https://garfield.com'},
  {'Date': new Date(2022, 4, 3), 'Title': '25 year anniversary', 'Link': 'https://www.google.com.au/#q=anniversary+gifts'},
];
var settings = {};
var element = document.getElementById('calendar');
caleandar(element, events, settings);
*/

fetch( 'https://thechefsworkshop.local/wp-json/tcw/v1/events' )
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