// Saves an Event Thumbnail via a call to our WP REST API EP
console.log( '🔔 save-event-thumbnail.js loaded...' );
const saveThumbnailButton = document.getElementById( 'generate-event-thumbnail' );
saveThumbnailButton.addEventListener('click', function(e){
  e.preventDefault();
  console.log('🔔 button was clicked! wpvars.ep = ', wpvars.ep );
  saveThumbnailButton.disabled = true;
  saveThumbnailButton.value = 'One moment. Generating thumbnail... (Page will reload when finished.)';
  //*
  fetch( wpvars.ep, {
    method: 'GET',
    headers: {
      'X-WP-Nonce': wpvars.nonce
    }
  }).then( function( response ){

    if( response.ok ){
      console.log( '✅ response.json()', response.json() );
      window.setTimeout( function(){
        saveThumbnailButton.value = 'Success! Reloading...';
        window.location.reload();
      }, 5000 );
    } else {
      console.log( '🚨 Promise.reject(response)', Promise.reject(response) );
    }
  }).catch( function(err){
    console.warn( '👋 There was an error!', err );
  });
  /**/
});

