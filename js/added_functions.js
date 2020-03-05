function delete_file(filepath_name) {
    // Default AJAX request type is GET so no need to define  
    $.ajax({
        url: 'php/delete_file.php',
        method   : "POST",
        data: {'file' : filepath_name },
        dataType: 'json', 
        success: function (response) {
            if( response.status === true ) {
                console.log('File Deleted!');
            }
            else console.log('Something Went Wrong!');
        }
        });
}


//compare les valeurs des propriétés d'objet
function isEquivalent(a, b) {
    // Create arrays of property names
    var aProps = Object.getOwnPropertyNames(a);
    var bProps = Object.getOwnPropertyNames(b);
    // If number of properties is different,
    // objects are not equivalent
    if (aProps.length != bProps.length) {
        return false;
    }
    for (var i = 0; i < aProps.length; i++) {
        var propName = aProps[i];
        // If values of same property are not equal,
        // objects are not equivalent
        if (a[propName] !== b[propName]) {
            return false;
        }
    }
    return true;
}
//var jangoFett = {
//    occupation: "Bounty Hunter",
//    genetics: "superb",
//    trucotro:"aa"
//};
//var bobaFett = {
//    occupation: "Bounty Hunter",
//    genetics: "superb",
//    trucotro:"aa"
//};
//// Outputs: true
//console.log(isEquivalent(bobaFett, jangoFett));



