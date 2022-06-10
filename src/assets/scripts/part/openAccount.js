export default function openAccount() {
	const open = document.getElementById("openAccount");
	const account = document.querySelector("form.woocommerce-form-login");
	const social = document.querySelector(".social-login");

	open.addEventListener("click", () => {
		account.classList.toggle("s-active");
	});
}
