import smoothscroll from "smoothscroll-polyfill";
import lozad from "lozad";
import hamburger from "./../part/hamburger";
import dropdown from "./../part/dropdown";
import mobileMenu from "./../part/mobileMenu";
import loginSwitch from "../part/loginSwitch"
import fdFilter from "./../part/filter"
import addToCart from "./../part/addToCart"
import layoutView from "./../part/layoutView"

export default {
	init() {
		// JavaScript to be fired on all pages


		// kick off the polyfill!
		smoothscroll.polyfill();



		addToCart();


		// Lazy load image with lozad.js https://github.com/ApoorvSaxena/lozad.js
		const observer = lozad(); // lazy loads elements with default selector as '.lozad'
		observer.observe();

		// Layout View
		const isLayout = document.getElementById('layoutSelector');
		if (typeof(isLayout) != 'undefined' && isLayout != null){
			layoutView();
		}

		const isLogin = document.getElementById('customer_login');
		if (typeof(isLogin) != 'undefined' && isLogin != null)
		{
		  loginSwitch();
		}

		const hasFilter = document.getElementById('fdFilterButton');
		if (typeof(hasFilter) != 'undefined' && hasFilter != null)
		{
			fdFilter();
		}

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

	finalize() {
		

	
		
	

	},
};
