var tag = [];

var split = text.replace(/[.,;:?!<>«»]/g, '');

//split = split.filter(function(a){return a !== ''});

var test = wordFreq(split);

function wordFreq(string) {
    var words = string.replace(/[.]/g, '').split(/\s/);
    words = cleanTokens(words);
    
    var freqMap = {};
    words.forEach(function(w) {
        if (!freqMap[w]) {
            freqMap[w] = 0;
        }
        freqMap[w] += 1;
    });

    var freqMapArray = Object.keys(freqMap).sort(function(a,b){return freqMap[a]-freqMap[b]});
    freqMapArray = freqMapArray.reverse();

    var freqFinal = new Array();

    var compteur = 0;

    for(var i = 0; i < freqMapArray.length; i++) {
      if(freqMapArray[i].length > 2) {
        freqFinal.push({text:freqMapArray[i], size:freqMap[freqMapArray[i]]});
        compteur += 1;
      }
      if(compteur > 50) {
        break;
      }
    }

    /*
    for(var w in freqMap) {
        if(w.length > 2) {
            compteur += 1;
            freqFinal.push({text:w, size:freqMap[w]});
        }
        if(compteur > 50) {
          break;
        }
    };*/

    return freqFinal;
}

d3.wordcloud()
        .size([500, 400])
        .selector('#wordcloud')
        .words(test)
        .start();


function cleanTokens(words) {
  var trashTokens = ["les","lors","sur","lui","c'est","des","la","le","de la","à la","qu'il","sur le","leurs","faire","avoir","qu'on","et la","et le","par","pour"];
  var res = [];

  for(let i=0; i < words.length; i++) {
    //console.log("tet");
    if(!isInArray(trashTokens, words[i])) {
      res.push(words[i])
    }
  }
  return res;
}

function isInArray(array, word) {
    return array.indexOf(word) > -1;
}


