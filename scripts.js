

let months = [{1:'Jan'}, {2:'Feb'}, {3:'Mar'}, {4:'Apr'}, {5:'May'}, {6:'Jun'},{7:'Jul'},{8:'Aug'}, {9:'Sep'}, {10:'Oct'}, {11:'Nov'}, {12:'Dec'}];


function addNewSource() {
	
	var subjectName = $("#subject").val();
	var now = new Date();
    var today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    var oneYearBack = new Date();
    oneYearBack.setDate(new Date().getDate() - 365);
    var newDate = new Date(oneYearBack.getFullYear(), oneYearBack.getMonth(), 1);
    var nbOdDaysInCurrMonth = new Date(oneYearBack.getFullYear(), oneYearBack.getMonth()+1, 0).getDate();
    
    oneYearBack = new Date(newDate);
    oneYearBack.setDate(newDate.getDate() + nbOdDaysInCurrMonth);
    var currDate = new Date(oneYearBack);
        
	var text = '';


    let d = 1;
    for (var k = 0, j = currDate.getMonth(); k < months.length; k++, j++) {
    	
    	j = j % months.length;

    	var monthNumber = Object.keys(months[currDate.getMonth()])[0];
		var monthName = Object.values(months[currDate.getMonth()])[0];
		
		let nbJours = new Date(currDate.getFullYear(),currDate.getMonth() +1,0).getDate();
		
		text += '<div class="month" id="month'+ subjectName + monthNumber +'"> <h3>'+ monthName +' '+currDate.getFullYear()+'</h3>' ;
		
		for (var i = 1; i <= nbJours; i++) {
			
			text += '<div class="tooltip"><input type="checkbox" onclick=checkboxClicked(this.id) id="'+ subjectName + '-'+ currDate.getTime()+'" class="regular-checkbox" />' +
		 	'<span class="tooltiptext">'+ currDate.toDateString() +'</span></div>';
			
			if (i%7 == 0) {
				text += '<br>';
			}	

			currDate.setDate(currDate.getDate() + 1);
	
			d++;

			if (compare(currDate,today) > 0) {
				break;
			}
		};

		
		text += "</div>";	

    };
    
    var newBlock = '<h3>' + subjectName + '</h3><div class="container" id="contribution-calendar-'+ subjectName +'">' + text+ '</div>';
	$("#contribution-calendar").append( newBlock);
}


function sameDate(date1, date2) {
	if (date1.getDate() == date2.getDate() && date1.getMonth() == date2.getMonth() && date1.getFullYear() == date2.getFullYear() ) {
		return true;
	} 
	return false;
}

function compare(date1, date2) {
	if (date1.getFullYear() == date2.getFullYear()) {
		if(date1.getMonth() == date2.getMonth()) {
			if(date1.getDate() == date2.getDate()) {
				return 0;
			} else if(date1.getDate() > date2.getDate()) {
				return 1;
			} else {
				return -1;
			}
		} else if(date1.getMonth() > date2.getMonth()) {
			return 1;
		} else {
			return -1;
		}
	} else if(date1.getFullYear() > date2.getFullYear()) {
		return 1;
	} else {
		return -1;
	}

}


function checkboxClicked(input) {
	var selectedDate = new Date();
	var inputSplitArr = input.split('-');
	var inputTime = inputSplitArr[1];
	var subject = inputSplitArr[0];
	selectedDate.setTime(inputTime);
	console.log("request="+selectedDate.toLocaleDateString());
	console.log($("#"+input).prop('checked'));
	console.log(input);
	console.log(subject);
	$("#"+input).prop('checked') === true? $("#"+input).addClass('contribution') : $("#"+input).removeClass('contribution');

	$.ajax({
		type:"POST",
	    url:"controller/controller.php",
	    data:{action: 'saveContribution', date: selectedDate.toDateString(), hasContribution: $("#"+input).prop('checked'), subject: subject},
	    dataType: 'JSON',
	    success: function(result, status){

	    },
	    error: function (resultat, status,error) {
	        console.log(resultat);
	        console.log(error);
	    }, 
	    complete: function(result){
	    	console.log("response" );
	    	console.log(result);
	    }
	});
}

function hasContrib(currDate, dateIndexArr, dataArr) {
	 if(dateIndexArr.indexOf(currDate) >= 0) {

	 	var validContrinution = dataArr.filter(x => new Date(x.date).toLocaleDateString() === currDate && x.hasContribution === true);
	 	return validContrinution.length > 0 ? 'contribution' : '';
	 }
	 return '';

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
    	var subjectIndex = Object.keys(result.responseJSON);
		var dateIndexArr = [];
		subjectIndex.forEach(x =>  
			dateIndexArr[x] = result.responseJSON[x].map(y => new Date(y['date']).toLocaleDateString())
		)
		
		var now = new Date();
        var today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        var oneYearBack = new Date();
        oneYearBack.setDate(new Date().getDate() - 365);
        var newDate = new Date(oneYearBack.getFullYear(), oneYearBack.getMonth(), 1);
        var nbOdDaysInCurrMonth = new Date(oneYearBack.getFullYear(), oneYearBack.getMonth()+1, 0).getDate();
        
        oneYearBack = new Date(newDate);
        oneYearBack.setDate(newDate.getDate() + nbOdDaysInCurrMonth);
        var currDate = new Date(oneYearBack);
        //console.log("today="+today.toLocaleDateString()+" oneYearBack="+oneYearBack.toLocaleDateString()+" newDate="+newDate.toLocaleDateString()+" nbOdDaysInCurrMonth="+nbOdDaysInCurrMonth);
        
        
		var textArr = [];
		subjectIndex.forEach(x => {
				textArr[x] = '' ;
		});


        let d = 1;
        for (var k = 0, j = currDate.getMonth(); k < months.length; k++, j++) {
        	
        	j = j % months.length;

        	var monthNumber = Object.keys(months[currDate.getMonth()])[0];
			var monthName = Object.values(months[currDate.getMonth()])[0];
			
			let nbJours = new Date(currDate.getFullYear(),currDate.getMonth() +1,0).getDate();
			
			//console.log("nbJours="+nbJours +" currDate="+currDate.toLocaleDateString());
			subjectIndex.forEach(x => {
				textArr[x] += '<div class="month" id="month'+ x + monthNumber +'"> <h3>'+ monthName +' '+currDate.getFullYear()+'</h3>' ;
			})
			//text += '<div class="month" id="month'+ monthNumber +'"> <h3>'+ monthName +' '+currDate.getFullYear()+'</h3>' ;
			


			for (var i = 1; i <= nbJours; i++) {

				subjectIndex.forEach(x => {
					textArr[x] += '<div class="tooltip"><input type="checkbox" onclick=checkboxClicked(this.id) id="'+ x + '-'+ currDate.getTime()+'" class="regular-checkbox '+ hasContrib(currDate.toLocaleDateString(), dateIndexArr[x], result.responseJSON[x])+'" />' +
				 	'<span class="tooltiptext">'+ currDate.toDateString() +'</span></div>';
					if (i%7 == 0) {
						textArr[x] += '<br>';
					}	
				})
			 	

				currDate.setDate(currDate.getDate() + 1);
		
				d++;

				if (compare(currDate,today) > 0) {
					break;
				}
			};

			subjectIndex.forEach(x => {
				textArr[x] += "</div>"	
			});
			

        };

			
		subjectIndex.forEach(x => {		
			var text = '<h3>' + x + '</h3><div class="container" id="contribution-calendar-'+x+'">' + textArr[x]+ '</div>';
			$("#contribution-calendar").append(text);

		});
		
		
		//$("#" + today.getTime()).prop("disabled", false);
    }
});

