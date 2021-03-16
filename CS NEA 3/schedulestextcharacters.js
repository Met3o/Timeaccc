function count_characters(string) {
	var length = string.length;
	document.getElementById("schedulesdescriptionlabel").innerHTML = 'Description: (' + length + '/500 characters)';
}

function count_name_characters(string) {
	var length = string.length;
	document.getElementById("schedulesnamelabel").innerHTML = 'Description: (' + length + '/30 characters)';
}
