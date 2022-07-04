	const modal = document.querySelector(".eo-sh-modals");
	const trigger = document.querySelector(".eo-sh-waiting-list-button");
	const closeButton = document.querySelector(".eo-sh-close-modal-button");
	const closeButton2 = document.querySelector(".eo-sh-close-button");



	function toggleModal() {
	    modal.classList.toggle("show-modal");
	}

	function windowOnClick(event) {
	    // if (event.target.closest('.show-modal') == modal) {
	    //     toggleModal();
	    // }
	    event.preventDefault();
	}

	trigger.addEventListener("click", toggleModal);
	closeButton.addEventListener("click", toggleModal);
	closeButton2.addEventListener("click", toggleModal);
	// window.addEventListener("click", windowOnClick);
