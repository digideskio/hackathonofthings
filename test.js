var wordString = 'Java is nice';
reverseWords(wordString);

function reverseWords(words) {
    var wordArray = words.split(' ');
    var arrayLength = wordArray.length;
    var last = arrayLength - 1;
    var reversedString = '';
    for (var i = last; i >= 0; i--) {
        reversedString += wordArray[i] + ' ';
    }
    console.log(reversedString);
}
