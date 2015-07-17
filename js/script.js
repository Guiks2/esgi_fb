$.fn.inView = function(){
    var viewport = {};
    viewport.top = $(window).scrollTop();
    viewport.bottom = viewport.top + $(window).height();
    var bounds = {};
    bounds.top = this.offset().top + 30;
    bounds.bottom = bounds.top + this.outerHeight();
    return ((bounds.bottom <= viewport.bottom) && (bounds.bottom >= viewport.top));     
};

$(document).scroll(function(){
	if ($('#load-more').inView()) {
	    $('#photos-candidats').append("<div class='photo'></div><div class='photo'></div><div class='photo'></div><br/><div class='photo'></div><div class='photo'></div><div class='photo'></div><br/><div class='photo'></div><div class='photo'></div><div class='photo'></div>");
	}
});

$("body").on("click", "#button-browse-image", function() {
  $("#browse-image").click();
});

$("body").on("change", "#browse-image", function() {
  var filename = $("#browse-image").val();
  var albums_buffer = "";

  if(filename !== 'undefined' && filename.length > 0) {
    var antislash_index = filename.lastIndexOf('\\');
    filename = filename.substring(antislash_index + 1 , filename.length);

    $.getJSON("../getAlbums.php", function(data) {
      $.each(data, function(index, value) {
        albums_buffer += "<li data-id='"+value.id+"'>"+value.name+"</li>";
      });
    }).success(function() {
        $("#dynamic-upload-zone").html("");
        $("#form-upload").append("<div id='upload-infos'>");
        $("#form-upload").append("<span>Choisissez l'album dans lequel ranger votre photo</span>");
        $("#form-upload").append("<br><span style='font-family: \"Trebuchet MS\"; font-size: 12px;'>["+ filename + "] </span><span style='position: absolute; right: -19px; top: 24pxpx;font-family: \"Trebuchet MS\"; font-size: 10px; cursor: pointer;' onclick='cancelUpload()'>(Annuler)</span><br>");
        $("#form-upload").append("<br><input id='album-name-hidden' name='album-id' type='hidden'/> <span id='default-choice'>-- Sélectionner l'album --</span>");
        $("#form-upload").append("<ul id='option-list'>"+albums_buffer+"<li data-node='crealbum'>Créer un nouvel album...</li></ul><div id='add-new-album'></div>");
        $("#form-upload").append("</div>");
    });
  }
});

$("body").on("click", "#default-choice", function() {
  $("#option-list").fadeToggle(150);
});

$("body").on("click", "#option-list li", function() {
  var attr = $(this).attr('data-id');

  if (typeof attr !== typeof undefined && attr !== false) {
    $("#default-choice").text($(this).text()).attr("data-id", $(this).attr("data-id"));
    $("#album-name-hidden").val($(this).attr("data-id"));
  } else {
    $("#album-name-hidden").val("-1");
  }
  
  $("#option-list").fadeOut(150);
  if($(this).attr("data-node") == "crealbum") {
    $("#add-new-album").html("<input type='text' id='new-album-name'></input><input type='submit' class='submit' value='Ok'></input>");
  }
  else {
    $("#add-new-album").html("");
     $("#add-new-album").html("<input type='submit' class='submit submit-alone' value='Ok'></input>");
  }
});

$(".container-parent").on("click", function() {
  $("#option-list").fadeOut(150);
});

function cancelUpload() {
  $("#upload-infos").remove();
  $("#dynamic-upload-zone").html('<div id="hide-button"><input type="file" name="browse-image" id="browse-image"></input></div><span id="button-browse-image" class="button-upload red-button">Je choisis une image de mon ordinateur...</span><br><span id="ou">OU</span><br><span id="button-facebook-image" class="button-upload red-button">Je choisis une image dans mes albums Facebook</span>');
}