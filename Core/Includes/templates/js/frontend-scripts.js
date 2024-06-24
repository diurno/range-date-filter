
function get_articles(date_from, date_to) {

	const formData = new FormData();
    formData.append( 'action', 'filter_articles' );
    formData.append( 'date_from', date_from );
    formData.append( 'date_to', date_to );


    fetch(rangedatef.ajaxurl, {
      method: 'POST',
      body: formData
    })
    .then( res => {
        if(!res.ok) {
            console.log(res);
            throw new Error("Could not fetch resource ")
        }
        return res.json()     
    })
    .then( data => {

    	console.log(data.articlesRecords.length);
    	let articleHtml = '';

    	if( data.articlesRecords.length > 0 ) {
			Object.entries(data.articlesRecords).forEach(([key, article]) => {
				articleHtml += '<div class="row"><div class="article"><div class="article--title"><a href="'+article.guid+'"><h2>'+article.post_name+'</h2></a></div><div class="article--body"><p>'+article.post_content+'</p></div></div></div>'
			});
		} else {
			articleHtml += '<div class="row"><h3>No articles found</h3></div>';
		}

		document.querySelector('#article-results').innerHTML = articleHtml;
        document.querySelector('#article-results').classList.remove("loading-results");

	})
	.catch( err => console.log( err ) );
}

jQuery(function () {
    jQuery("#slider-range").slider({
        range: true,
        min: new Date('1990.01.01').getTime() / 1000,
        max: new Date('2025.12.31').getTime() / 1000,
        step: 95000,
        values: [new Date('2000.01.01').getTime() / 1000, new Date('2022.02.01').getTime() / 1000],
        slide: function (event, ui) {
         
        },
        stop: function( event, ui ) {
        	jQuery("#date-selected").val((new Date(ui.values[0] * 1000).toDateString()) + " - " + (new Date(ui.values[1] * 1000)).toDateString());
            jQuery('#date-from').val(new Date(ui.values[0] * 1000).toDateString());
            jQuery('#date-to').val(new Date(ui.values[1] * 1000).toDateString());
            let fromTimestamp=new Date(Date.parse(new Date(ui.values[0] * 1000).toDateString())).toLocaleDateString("en-US");
            let toTimestamp=new Date(Date.parse(new Date(ui.values[1] * 1000).toDateString())).toLocaleDateString("en-US");
            document.querySelector('#article-results').classList.add("loading-results");
            //setTimeout(() => {
                get_articles(fromTimestamp, toTimestamp);
//            }, 1000);        
          
        }
    });
});
