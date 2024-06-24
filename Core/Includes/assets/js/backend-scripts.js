/*------------------------ 
Backend related javascript
------------------------*/
window.addEventListener('load', (event) => {

	const displayMessage = (msg, msgClass) => {
		const messageBox = document.querySelector('.msg');
		messageBox.classList.remove("hide");
		messageBox.classList.add(msgClass);
	    messageBox.innerHTML =  "Changes Saved";

	    setTimeout(function(){ 
	    	messageBox.classList.add("hide") }
	    , 2000);

	}


	const articles_save_post_types = () => {

		
		const _form = document.querySelector('#post-types-form');
		
		const formData = new FormData(_form);
	    formData.append( 'action', 'filter_articles_save_post_types' );
	    
	    fetch(rangedatef.ajaxurl, {
	      method: 'POST',
	      body: formData
	    })
	    .then( res => {
	        if(!res.ok) {
	        	displayMessage("Could not fetch resource Saved","error");
	        	throw new Error("Could not fetch resource ")
	        }
	        return res.json()     
	    })
	    .then( data => {

	    	displayMessage("Changes Saved","success");
	    	//messageBox.classList.add("success");
	    	//messageBox.innerHTML =  "Changes Saved";
		})
		.catch( err => { displayMessage("Could not fetch resource Saved","error"); });
	}

	const saveSettingsButton = document.querySelector('#post-type-submit');
	if(saveSettingsButton) {
		saveSettingsButton.addEventListener("click", function(){
			articles_save_post_types();
		})
	}

});
