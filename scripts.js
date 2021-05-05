
let fullYear = new Date().getFullYear();
let months = [{1:'Jan'}, {2:'Feb'}, {3:'Mar'}, {4:'Apr'}, {5:'May'}, {6:'Jul'},{7:'July'},{8:'Aug'}, {9:'Sept'}, {10:'Oct'}, {11:'Nov'}, {12:'Dec'}];
var text='';

for (var m = 0; m < months.length; m++) {
	var monthNumber = Object.keys(months[m])[0];
	var monthName = Object.values(months[m])[0];

	let nbJours = new Date(fullYear,monthNumber,0).getDate();	
	text += '<div class="month" id="month'+ monthNumber +'"> <h3>'+ monthName +'</h3>' ;
	for (var i = 1; i <= nbJours; i++) {
	 text += '<input type="checkbox" id="checkbox'+monthNumber+''+i+'" class="regular-checkbox" />';
	 if (i%7 == 0) {
	 	text += '<br>'
	 };
	};

	text += "</div>"
};



$("#contribution-calendar").append(text);

