var spanmark = 1;
var vh_content = new Array();
function getspan(spanid) {
    if (document.getElementById) {
        return document.getElementById(spanid);
    } else if (window[spanid]) {
        return window[spanid];
    }
    return null;
}
function toggle(spanid) {
    if (getspan(spanid).innerHTML == "") {
        getspan(spanid).innerHTML = vh_content[spanid];
        getspan(spanid + "indicator").innerHTML = '<img src="img/open.gif" alt="Opened folder" />';
    } else {
        vh_content[spanid] = getspan(spanid).innerHTML;
        getspan(spanid).innerHTML = "";
        getspan(spanid + "indicator").innerHTML = '<img src="img/closed.gif" alt="Closed folder" />';
    }
}
function collapse(spanid) {
    if (getspan(spanid).innerHTML !== "") {
        vh_content[spanid] = getspan(spanid).innerHTML;
        getspan(spanid).innerHTML = "";
        getspan(spanid + "indicator").innerHTML = '<img src="img/closed.gif" alt="Closed folder" />';
    }
}
function expand(spanid) {
    getspan(spanid).innerHTML = vh_content[spanid];
    getspan(spanid + "indicator").innerHTML = '<img src="img/open.gif" alt="Opened folder" />';
}
function expandall() {
    for (i = 1; i <= vh_numspans; i++) {
        expand("comments_" + String(i));
    }
}
function collapseall() {
    for (i = vh_numspans; i > 0; i--) {
        collapse("comments_" + String(i));
    }
}

function setChecked(obj,from,to){
    for (var i=from; i<=to; i++) {
        if (document.getElementById('quiz_' + i)) {
            document.getElementById('quiz_' + i).checked = obj.checked;
        }
    }
}
function mytoggle(id) {
    if ($("#"+id+"indicator img").attr("alt") == "Opened folder") {
        $("#"+id).hide();
        $("#"+id+"indicator img").attr("alt", "Closed folder");
        $("#"+id+"indicator img").attr("src", "img/closed.gif");
    } else {
        $("#"+id).show();
        $("#"+id+"indicator img").attr("alt", "Opened folder");
        $("#"+id+"indicator img").attr("src", "img/open.gif");
    }
}

function mycollapse(id) {
    if ($("#"+id+"indicator img").attr("alt") == "Opened folder") {
        $("#"+id).hide();
        $("#"+id+"indicator img").attr("alt", "Closed folder");
        $("#"+id+"indicator img").attr("src", "img/closed.gif");
    } else {
        $("#"+id).show();
        $("#"+id+"indicator img").attr("alt", "Opened folder");
        $("#"+id+"indicator img").attr("src", "img/open.gif");
    }
}

function mycollapse1(id) {
    $("#"+id).hide();
    $("#"+id+"indicator img").attr("alt", "Closed folder");
    $("#"+id+"indicator img").attr("src", "img/closed.gif");
}

function myexpand1(id) {
    $("#"+id).show();
    $("#"+id+"indicator img").attr("alt", "Opened folder");
    $("#"+id+"indicator img").attr("src", "img/open.gif");
}

function mycollapseall() {
    for (i = vh_numspans; i > 0; i--) {
        mycollapse1("comments_" + String(i));
    }
    return false;
}

function myexpandall() {
    for (i = vh_numspans; i > 0; i--) {
        myexpand1("comments_" + String(i));
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


function expandall2() {
    if (window.spanmark == 1) {
        for (i = 1; i <= vh_numspans; i++) {
            expand("comments_" + String(i));
        }
        window.spanmark = 2;
    }
}
function setcheckedbyid(ids) {
    var pos=ids.indexOf(",");
    if (pos>=0) {
        var myArray = ids.split(","); 
        for (i = 0; i < myArray.length; i++) {
            document.getElementById("quiz_" + myArray[i]).checked = true;
        }
    } else {
        document.getElementById("quiz_" + ids).checked = true;
    }
}

function uncheckall() {
    void(d=document);
    void(el=d.getElementsByTagName('INPUT'));
    for(i=0;i<el.length;i++) {
        void(el[i].checked=0);
    }
}

function checkall() {
    void(d=document);
    void(el=d.getElementsByTagName('INPUT'));
    for(i=0;i<el.length;i++) {
        void(el[i].checked=1);
    }
}

function validateForm(form) {
    if (isChosen(form.book)) {
        return true;
    }
    return false;
}