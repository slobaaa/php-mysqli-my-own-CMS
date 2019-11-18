$( document ).ready(function() {
ClassicEditor
        .create(document.querySelector( '#body' ) )
        .catch(error => {
            // console.error(error);
        });
$("#selectAllBoxes").click(function(event) {
    if(this.checked) {
        $('.checkBoxes').each(function() {
            this.checked = true;
        });
    } else {
        $('.checkBoxes').each(function() {
            this.checked = false;
        });
    }
});
     
// onaj znak za load
// var div_box = "<div id='load-screen'><div id='loading'></div></div>";
// $("body").prepend(div_box);

// $('#load-screen').delay(700).fadeOut(600, function() {
//     $(this).remove();
// });

// function loadUsersOnLine() {
//     $.get("functions.php?onLineusers=result", function() {
//         $(".usersonLine").text(data);
//     });

// }
// setInterval(function() {
//     loadUsersOnLine();
// }, 500);

});