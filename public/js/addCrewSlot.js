$(document).ready(function(){
    var nbSlots = 1;
    var roles = [];

    $('.captain-role option').each(function(){
        roles.push([$(this).val(), $(this).text()]);

    });

    $(".crew-slots").append(generateDivSlot(nbSlots));


    $('#add_slot').click(function(){
        nbSlots++;
        $(".crew-slots").append(generateDivSlot(nbSlots));

    })



    $(document).on('click', '.remove-slot', function(){
        var crewSlot = $(this).parent("div");
        $('#' + crewSlot.attr('id')).remove();

        renameCrewSlotDiv();
    })

    function generateDivSlot(nbSlots)
    {

        var str =  "<div id=slot" + nbSlots + " class='crew-slot'>";
        str     += "    <label for='crew-role'>Slot " + nbSlots + " : </label>";
        str     += "    <select name='crew-role[]'>";
        $(roles).each(function(){
            str += "        <option value='" + $(this)[0] + "'>" +  $(this)[1] + "</option>";
        })
        str     += "    </select>";
        if(nbSlots > 1){
            str += "    <button type='button' class='btn btn-default btn-xs remove-slot'>";
            str += "        <span class='glyphicon glyphicon-remove' aria-hidden='true'></span>";
            str += "    </button>";
        }
        str     += "<br />";
        str     += "</div>";


        return str;
    }

    function renameCrewSlotDiv()
    {
        var slotNum = 0;
        $(".crew-slot").each(function(){
            slotNum++;
            $(this).attr('id', 'slot' + slotNum);
            $(this).find("label").text("Slot " + slotNum + " : ");

        })
        nbSlots = slotNum;
    }

})
