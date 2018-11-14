var mainTables = document.getElementsByClassName("mainTable");
var historicTables = document.getElementsByClassName("historicTable");

var expand = document.getElementsByClassName("expand");

for(let i = 0; i < historicTables.length; i++) {
	historicTables[i].style.display = "none";
}

//console.log(historicTables);

for(let i = 0; i < expand.length; i++) {
	expand[i].addEventListener('click', function () {
		if(historicTables[i].style.display === "table") {
			historicTables[i].style.display = "none";
			expand[i].innerHTML = "[+]";
		}
		else {
			historicTables[i].style.display = "table";	
			expand[i].innerHTML = "[-]";
		}
	});
}

var trQuota = document.querySelectorAll(".quotas");
highlightOdd();

function hideAndDisplay() {
	if (simpleTree.style.display === "block") {
        simpleTree.style.display = "none";
        zoomableTree.style.display = "block";
    } else {
        simpleTree.style.display = "block";
        zoomableTree.style.display = "none";
    }
}


function highlightOdd() {
	var maxHome = 0;
	var maxDraw = 0;
	var maxAway = 0;
	var start = 0;

	for(let i = 0; i < trQuota.length; i++) {		
		let td = trQuota[i].querySelectorAll('td');

		if(td[1].textContent > maxHome) {
			maxHome = td[1].textContent;
		}
		if(td[2].textContent > maxDraw) {
			maxDraw = td[2].textContent;
		}
		if(td[3].textContent > maxAway) {
			maxAway = td[3].textContent;
		}

		if(trQuota[i].nextSibling == null || trQuota[i].nextSibling.classList.contains("stopQuotta")) {
			applyHighlightStyle(maxHome, maxDraw, maxAway, start, i);
			
			maxHome = 0;
			maxDraw = 0;
			maxAway = 0;
			start = i+1;
		}
	}
}

function applyHighlightStyle(home, draw, away, start, end) {
	console.log(start +' '+end);
	console.log(home + ' '+draw+' '+away);

	for(let i = start; i <= end; i++) {
		let td = trQuota[i].querySelectorAll('td');
		var boolGoodBookmaker = false;

		if(td[1].textContent == home) {
			td[1].style.fontWeight = 'bold';
			td[1].style.color = 'red';
			td[1].style.backgroundColor = 'lightgrey';
		}
		if(td[2].textContent == draw) {
			td[2].style.fontWeight = 'bold';
			td[2].style.color = 'red';
			td[2].style.backgroundColor = 'lightgrey';
		}
		if(td[3].textContent == away) {
			td[3].style.fontWeight = 'bold';
			td[3].style.color = 'red';
			td[3].style.backgroundColor = 'lightgrey';
		}

		if(td[1].textContent == home && td[2].textContent == draw && td[3].textContent == away) {
			td[0].style.fontWeight = 'bold';
			td[0].style.backgroundColor = 'lightgrey';
			boolGoodBookmaker = true;
		}
		if(trQuota[i].nextSibling == null || trQuota[i].nextSibling.classList.contains("stopQuotta")) {
			break;
		}
		/*else if((td[1].textContent == home && td[2].textContent == draw)
			|| (td[1].textContent == home && td[3].textContent == away)
			|| (td[2].textContent == draw && td[3].textContent == away)){
			td[0].style.fontWeight = 'bold';
			td[0].style.backgroundColor = 'lightgrey';
		}*/
	}
	if(!boolGoodBookmaker) {
		for(let i = start; i < end+1; i++) {

		let td = trQuota[i].querySelectorAll('td');

		if((td[1].textContent == home && td[2].textContent == draw)
			|| (td[1].textContent == home && td[3].textContent == away)
			|| (td[2].textContent == draw && td[3].textContent == away)){
			td[0].style.fontWeight = 'bold';
			td[0].style.backgroundColor = 'lightgrey';
		}

		if(trQuota[i].nextSibling == null || trQuota[i].nextSibling.classList.contains("stopQuotta")) {
			break;
		}
	}
	}
}