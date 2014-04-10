/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    var count = 1;
    var options = "";
    $.getJSON("http://localhost/iusm/events/category.json", function(result) {
        $.each(result, function(key, value) {
            console.log(key,value);
            options = options + "<option value=\"" + key + "\">" + value + "</option>";
        });
    });
    $("#add").click(function() {
        var intId = $("#itemdata div").length;
        var fieldWrapper = $("<div class=\"form-group\" id=\"field \"/>");
        var itemNo = $("<div class=\"col-sm-offset-2 col-sm-1\"> <input type=\"text\" class=\"form-control itemNo\" name=\"itemNo[]\" size=2 value=" + count + " required=\"yes\" /> </div>");
        var itemName = $("<div class=\"col-sm-2\"><input class=\"fieldtype itemName\" type=\"text\" name=\"itemName[]\" required=\"yes\" /></div>");
        var category = $("<div class=\"col-sm-1\"> <select class=\"category\" name=\"category[]\">" + options + "</select></div>");
        var quantity = $("<div class=\"col-sm-1\"><input class=\"fieldtype quantity\" type=\"text\" name=\"quantity[]\" required=\"yes\" /></div>");
        var description = $("<div class=\"col-sm-2\"><input class=\"fieldtype description\" type=\"text\" name=\"description[]\" /></div>");
        var removeButton = $("<div class=\"col-sm-1\"><button type=\"button\">-</button></div> </div>");
        removeButton.click(function() {
            $(this).parent().remove();
            count = count - 1 ;
        });
        fieldWrapper.append(itemNo);
        fieldWrapper.append(itemName);
        fieldWrapper.append(category);
        fieldWrapper.append(quantity);
        fieldWrapper.append(description);
        fieldWrapper.append(removeButton);
        $("#itemdata").append(fieldWrapper);
        count = count + 1;
    });
});


