$(document).ready(function(){


    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(".filter-search").click(function(){
        var languages = [];
        $(".language").each(function(){
            if($(this).is(":checked"))
            {
                languages.push($(this).val());
            }

        })
        var request = $.post(
            "/starrew_master/public/mission/search",
            {
                goal:$("#cbxSearchGoal").val(),
                role:$("#cbxSearchRole").val(),
                languages:languages,
                dateTime:$("#datetimepicker").val(),
                _token:CSRF_TOKEN
            },
            "text"
        );

        request.done(function(data){
            $("#missionList").html("");
            $("#missionList").html(data);
        })
    });
})
