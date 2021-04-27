import smoothscroll from "smoothscroll-polyfill";
import lozad from "lozad";
import hamburger from "./../part/hamburger";
import dropdown from "./../part/dropdown";
import mobileMenu from "./../part/mobileMenu";

export default {
	init() {
		// JavaScript to be fired on all pages


		// kick off the polyfill!
		smoothscroll.polyfill();



		// Lazy load image with lozad.js https://github.com/ApoorvSaxena/lozad.js
		const observer = lozad(); // lazy loads elements with default selector as '.lozad'
		observer.observe();

	
	},

	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
		const mediaQuery = window.matchMedia('(min-width: 1040px)')



	


		function handleTabletChange(e) {
			// Check if the media query is true
			if (!e.matches) {
			 	// Hamburger event listener
				hamburger();

				//  MOBILE menu
				mobileMenu();
			}else{
				// Dropdown Menu
				dropdown();
			}
		  }
		  
		  // Register event listener
		  mediaQuery.addListener(handleTabletChange)
		  
		  // Initial check
		  handleTabletChange(mediaQuery)

	},
};
