$(document).ready(function(){
    $("#id_publisher").change( function(){ 
        $("#selectthebook").html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        $.get("ajax_view_get_bookslist.php?" + $(this).val(), function(data){
            $("#selectthebook").html(data);
            $("#id_reader_view_take_quiz_btn").show();
        });
    });
});

function validateForm(form) {
    if (isChosen(form.book)) {
        return true;
    }
    return false;
}

function isChosen(select) {
   if (select.selectedIndex == -1) {
       alert("Please choose book!");
       return false;
   } else {
       return true;
   }
}