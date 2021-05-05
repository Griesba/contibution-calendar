
let fullYear = new Date().getFullYear();
let months = [{1:'Jan'}, {2:'Feb'}, {3:'Mar'}, {4:'Apr'}, {5:'May'}, {6:'Jul'},{7:'July'},{8:'Aug'}, {9:'Sept'}, {10:'Oct'}, {11:'Nov'}, {12:'Dec'}];



function sameDate(date1, date2) {
	if (date1.getDate() == date2.getDate() && date1.getMonth() == date2.getMonth() && date1.getFullYear() == date2.getFullYear() ) {
		return true;
	} 
	return false;
}


        $.ajax({
            type:"POST",
            url:"controller/controller.php",
            data:{action: 'getContributions'},
            dataType: 'JSON',
            success: function(result, status){

            },
            error: function (resultat, status,error) {
                console.log(resultat);
                console.log(error);
            }, 
            complete: function(result){
            	
                console.log(result.responseJSON);


                var text='';

				for (var m = 0; m < months.length; m++) {
					var monthNumber = Object.keys(months[m])[0];
					var monthName = Object.values(months[m])[0];

					let nbJours = new Date(fullYear,monthNumber,0).getDate();
					text += '<div class="month" id="month'+ monthNumber +'"> <h3>'+ monthName +'</h3>' ;
					for (var i = 1; i <= nbJours; i++) {
						var today = fullYear + '-' + monthNumber + '-' + i;
						console.log(today);

					 text += '<input type="checkbox" id="checkbox'+monthNumber+''+i+'" class="regular-checkbox" disabled/>';
					 if (i%7 == 0) {
					 	text += '<br>'
					 };
					};

					text += "</div>"
				};
				$("#contribution-calendar").append(text);
            }
        });

