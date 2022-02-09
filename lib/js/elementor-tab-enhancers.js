let intViewportWidth = window.innerWidth;
console.log('🔔 intViewportWidth = ', intViewportWidth );

const activeClass = 'elementor-active';
const mobileBreakpoint = '767'; // Our breakpoint ensures this code only runs on small screens.
const mobileTabButtons = document.querySelectorAll('.elementor-tab-mobile-title');
const allTabsContent = document.querySelectorAll('.elementor-tab-content');

// Flexbox styling for desktop tabs
if( intViewportWidth > mobileBreakpoint ){
  if( desktopTabWrappers ){
    desktopTabWrappers.forEach( function( wrapper ){
      wrapper.display = 'flex';
      wrapper.justifyContent = 'space-around';
    });
  }
}

if( intViewportWidth <= mobileBreakpoint ){
  // Apply click handling to the Tab buttons
  if( mobileTabButtons ){
    mobileTabButtons.forEach(function(mobileTabButton){
      mobileTabButton.addEventListener('click',function(e){
        e.preventDefault();
        tabClickHandler(this);
      });
    });
  }

  // Close all tabs
  const tabgroups = document.querySelectorAll('.elementor-widget-tabs .elementor-tabs-content-wrapper');
  if(tabgroups){
    let delay = 300; // We must wait for the tab groups to initialize.
    setTimeout(function(){
      tabgroups.forEach(function(tabgroup){
        // Set the tab buttons to inactive state:
        let buttons = tabgroup.getElementsByClassName('elementor-tab-mobile-title');
        if( buttons ){
          console.log('🔔 Updating button state.' );
          for( let i = 0; i < buttons.length; i++ ){
            buttons[i].classList.remove( activeClass );
          }
        }
        // Hide the tab content:
        let contents = tabgroup.getElementsByClassName('elementor-tab-content');
        if( contents ){
          console.log('🔔 Hiding tab contents.' );
          for( let i = 0; i < contents.length; i++ ){
            contents[i].classList.remove('elementor-active');
            contents[i].style.display = 'none';
            contents[i].setAttribute('hidden', '');
          }
        }
      });
    },delay);
  }
}

/**
 * Click handling for Tab buttons.
 *
 * @param {Object}  Tab button element.
 *
 * @return {undefined}
 */
const tabClickHandler = function( tabButton ){
  let buttonName = tabButton.textContent;
  console.log(`\n👋 "${buttonName}"  was clicked.`);
  let thisContent = tabButton.nextElementSibling;

  if( tabButton.classList.contains( activeClass ) || thisContent.classList.contains('elementor-active') ){
    //removeActiveClass(); // "Zero out" all tab buttons.
    console.log(`\t☑️ Deactivating "${buttonName}" button.`);
    tabButton.classList.remove( activeClass );
    console.log(`\t☑️ Hiding "${buttonName}" content.`);
    thisContent.classList.remove('elementor-active');
    thisContent.style.display = 'none';
    thisContent.setAttribute('hidden', 'hidden');
  } else {
    removeActiveClass(); // "Zero out" all tab buttons.
    //hideAllContent(); // Hide all content.
    console.log(`\t✅ Activating "${buttonName}" button.`);
    tabButton.classList.add( activeClass );
    console.log(`\t✅ Showing "${buttonName}" content.`);
    thisContent.classList.add('elementor-active');
    thisContent.style.display = 'block';
    thisContent.setAttribute('hidden', '');
  }
}

/**
 * Removes the $activeClass from all buttons.
 */
const removeActiveClass = function(){
  console.log(`🔔 Removing .${activeClass} from all tab buttons...`)
  mobileTabButtons.forEach(function(mobileTabButton){
    mobileTabButton.classList.remove( activeClass );
  });
}

/**
 * Hides all tab content.
 */
const hideAllContent = function(){
  console.log(`🔔 Hiding all tab content...`);
  allTabsContent.forEach(function(tabContent){
    tabContent.classList.remove( activeClass );
    tabContent.style.display = 'none';
  });
}