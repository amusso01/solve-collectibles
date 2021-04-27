export default function hamburger() {
	const burger = document.getElementById("hamburger");
	const mainMenu = document.querySelector(".primary-menu");
	const search = document.querySelector(".site-header__mobile .woocommerce-product-search");
	const htmlElement = document.querySelector("html");
	burger.addEventListener("click", function (e) {
		mainMenu.classList.toggle("showMobile");
		search.classList.toggle("showMobile");
		burger.classList.toggle("is-active");

		// prevent content scrolling
		htmlElement.classList.toggle("noscroll");
	});
}
