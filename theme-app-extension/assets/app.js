	const modal = document.querySelector(".eo-sh-modals");
	const trigger = document.querySelector(".eo-sh-waiting-list-button");
	const closeButton = document.querySelector(".eo-sh-close-button");
	const clButton = document.querySelector("#eo-sh-close-button");

	function toggleModal() {
	    modal.classList.toggle("show-modal");
	}

	function windowOnClick(event) {
	    if (event.target === modal) {
	        toggleModal();
	    }
	}

	trigger.addEventListener("click", toggleModal);
	closeButton.addEventListener("click", toggleModal);
	clButton.addEventListener("click", toggleModal);
	window.addEventListener("click", windowOnClick);
