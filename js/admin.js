$(document).ready(function(){
    $("#id_reader_select_group_box").change( function(){ 
        window.location = $("#id_reader_select_group_url").val()+'&grid='+$(this).val();
    });
    
    $("#id_reader_choose_perpage_box").change( function(){ 
        window.location = $("#id_reader_choose_perpage_url").val()+'&perpage='+$(this).val();
    });
    
    $("#id_reader_select_term_box").change( function(){ 
        window.location = $("#id_reader_select_term_url").val()+'&ct='+$(this).val();
    });
    
    $("#id_reader_changereaderlevel_publisherselect").change( function(){ 
        window.location = $("#id_reader_changereaderlevel_publisherselect_url").val()+'&publisher='+$(this).val();
    });
    
    $("#id_reader_changereaderlevel_levelselect").change( function(){ 
        window.location = $("#id_reader_changereaderlevel_levelselect_url").val()+'&level='+$(this).val();
    });
    
    $(".class_reader_promotion_stop_box").change( function(){ 
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=promotionstop&" + $(this).val());
    });
    
    $(".class_reader_change_goal_box").change( function(){ 
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=setgoal&" + $(this).val());
    });
    
    $(".class_reader_nopromote_box").change( function(){ 
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=nopromote&" + $(this).val());
    });
    
    $(".class_reader_changelevels_box").change( function(){ 
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?" + $(this).val());
    });
    
    $(".class_reader_change_length_box").change( function(){ 
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=changelength&" + $(this).val());
    });
    
    $(".class_reader_change_difficulty_box").change( function(){ 
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=changedifficulty&" + $(this).val());
    });
    
    $('.class_reader_change_publishertitle_text').keypress(function(e){
        if(e.which == 13){
            var p = $(this).parent();
            p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
            p.load("ajax_admin.php?f=publishertitletext&" + $(this).attr("data-url")+'&publishertitle='+encodeURIComponent($(this).val()));
        }
    });
    
    $('.class_reader_change_level_text').keypress(function(e){
        if(e.which == 13){
            var p = $(this).parent();
            p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
            p.load("ajax_admin.php?f=leveltext&" + $(this).attr("data-url")+'&level='+encodeURIComponent($(this).val()));
        }
    });
    
    $('.class_reader_change_words_text').keypress(function(e){
        if(e.which == 13){
            var p = $(this).parent();
            p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
            p.load("ajax_admin.php?f=wordstext&" + $(this).attr("data-url")+'&words='+encodeURIComponent($(this).val()));
        }
    });
    
    $("#id_reader_changereaderlevel_masspublisher_click").click( function(){ 
        var id = $('#id_reader_id').val();
        var v1 = encodeURIComponent($('#id_reader_changereaderlevel_masspublisher_from').val());
        var v2 = encodeURIComponent($('#id_reader_changereaderlevel_masspublisher_to').val());
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=changereaderlevel_masspublisher&id="+id+"&masspublisherfrom=" + v1 + '&masspublisherto=' + v2);
    });
    
    $("#id_reader_changereaderlevel_masslevel_click").click( function(){ 
        var id = $('#id_reader_id').val();
        var publisher = encodeURIComponent($('#id_reader_changereaderlevel_publisher_id').val());
        var v1 = encodeURIComponent($('#id_reader_changereaderlevel_masslevel_from').val());
        var v2 = encodeURIComponent($('#id_reader_changereaderlevel_masslevel_to').val());
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=changereaderlevel_masslevel&id="+id+"&masslevelfrom=" + v1 + '&masslevelto=' + v2 + '&publisher=' + publisher);
    });
    
    $("#id_reader_changereaderlevel_masslength_click").click( function(){ 
        var id = $('#id_reader_id').val();
        var publisher = encodeURIComponent($('#id_reader_changereaderlevel_publisher_id').val());
        var level = encodeURIComponent($('#id_reader_changereaderlevel_level_id').val());
        var v1 = encodeURIComponent($('#id_reader_changereaderlevel_masslength_from').val());
        var v2 = encodeURIComponent($('#id_reader_changereaderlevel_masslength_to').val());
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=changereaderlevel_masslength&id="+id+"&masslengthfrom=" + v1 + '&masslengthto=' + v2 + '&publisher=' + publisher + '&level=' + level);
    });
    
    $("#id_reader_changereaderlevel_massdifficulty_click").click( function(){ 
        var id = $('#id_reader_id').val();
        var publisher = encodeURIComponent($('#id_reader_changereaderlevel_publisher_id').val());
        var level = encodeURIComponent($('#id_reader_changereaderlevel_level_id').val());
        var v1 = encodeURIComponent($('#id_reader_changereaderlevel_massdifficulty_from').val());
        var v2 = encodeURIComponent($('#id_reader_changereaderlevel_massdifficulty_to').val());
        var p = $(this).parent();
        p.html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        p.load("ajax_admin.php?f=changereaderlevel_massdifficulty&id="+id+"&massdifficultyfrom=" + v1 + '&massdifficultyto=' + v2 + '&publisher=' + publisher + '&level=' + level);
    });
    
    $("#id_reader_adjustscores_publisher").change( function(){ 
        $("#selectthebook").html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        $.get("ajax_view_get_bookslist.php?onlypub=1&" + $(this).val(), function(data){
            $("#selectthebook").html(data);
        });
    });
    
    $("#id_publisher_noquizzes").change( function(){ 
        $("#selectthebook").html('<img src="img/zoomloader.gif" width="16" height="16" alt="loader" title="loader" />');
        $.get("ajax_view_get_bookslist_noquiz.php?" + $(this).val(), function(data){
            $("#selectthebook").html(data);
            $("#id_reader_assignpointsbookshavenoquizzes_submit_btn").show();
        });
    });
});