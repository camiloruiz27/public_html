document.addEventListener("DOMContentLoaded", function(){ window.addEventListener( 'load', function() {
	UAGBButtonChild.init( '.uagb-block-0a5b8a7b' );
});
window.addEventListener( 'load', function() {
	UAGBButtonChild.init( '.uagb-block-c5757938' );
});
window.addEventListener( 'load', function() {
	UAGBButtonChild.init( '.uagb-block-eca6baf5' );
});
				window.addEventListener( 'DOMContentLoaded', () => {
					const blockScope = document.querySelector( '.uagb-block-5189427a' );
					if ( ! blockScope ) {
						return;
					}

					const anchorElement = blockScope.querySelector('a');
					if (!anchorElement) {
						return;
					} 

					 
					blockScope.addEventListener('keydown', (event) => {
						if ( 13 === event.keyCode || 32 === event.keyCode ) {
							event.preventDefault();
							 
							anchorElement.click();	
						}
					} );
				} );
			window.addEventListener( 'load', function() {
	UAGBButtonChild.init( '.uagb-block-f02f7e27' );
});
 });