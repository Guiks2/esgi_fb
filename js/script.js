/* Définition de l'extension inView détectant si l'élément est dans le viewport */
$.fn.inView = function(){
    var viewport = {};
    viewport.top = $(window).scrollTop();
    viewport.bottom = viewport.top + $(window).height();
    var bounds = {};
    bounds.top = this.offset().top + 30;
    bounds.bottom = bounds.top + this.outerHeight();
    return ((bounds.bottom <= viewport.bottom) && (bounds.bottom >= viewport.top));     
};

/* Au scroll, vérifier si la zone est en vue */
$(document).scroll(function(){
	
});

/* Clic sur "Je choisis une image de mon ordinateur" */
$("body").on("click", "#button-browse-image", function() {
  $("#browse-image").click();
});

/* Lorsq'une image est séléctionnée sur le disque */
$("body").on("change", "#browse-image", function() {
  var filename = $("#browse-image").val();
  var albums_buffer = "";

  /* Vérifier que le fichier existe */
  if(filename !== 'undefined' && filename.length > 0) {
    var antislash_index = filename.lastIndexOf('\\');
    filename = filename.substring(antislash_index + 1 , filename.length);

    /* Récupération de la liste des albums de l'utilisateur pour la droplist */
    $.getJSON("../getAlbums.php", function(data) {
      $.each(data, function(index, value) {
        albums_buffer += "<li data-id='"+value.id+"'>"+value.name+"</li>";
      });
    }).success(function() {
        /* Mise à jour de la zone lorsque de la zone d'upload */
        $("#dynamic-upload-zone").html("");
        $("#form-upload").append("<div id='upload-infos'>"+
                                  "<span>Choisissez l'album dans lequel ranger votre photo</span>"+
                                  "<br><span style='font-family: \"Trebuchet MS\"; font-size: 12px;'>["+ filename + "] </span><span style='position: absolute; right: -19px; top: 24pxpx;font-family: \"Trebuchet MS\"; font-size: 10px; cursor: pointer;' onclick='cancelUpload()'>(Annuler)</span><br>"+
                                  "<br><input id='album-name-hidden' name='album-id' type='hidden'/> <span id='default-choice'>-- Sélectionner l'album --</span>"+
                                  "<ul id='option-list'>"+albums_buffer+"<li data-node='crealbum'>Créer un nouvel album...</li></ul><div id='add-new-album'></div>"+
                                  "</div>");
    });
  }
});

/* Déploiement de la liste albums */
$("body").on("click", "#default-choice", function() {
  $("#option-list").fadeToggle(150);
});

/* Sélection d'un item de la liste */
$("body").on("click", "#option-list li", function() {
  var attr = $(this).attr('data-id');

  /* Si un nom d'album est choisi */
  if (typeof attr !== typeof undefined && attr !== false) {
    $("#default-choice").text($(this).text()).attr("data-id", $(this).attr("data-id"));
    $("#album-name-hidden").val($(this).attr("data-id"));
  } else {
    $("#album-name-hidden").val("-1");
  }
  
  $("#option-list").fadeOut(150);

  /* Si "Créer un album" est choisi */
  if($(this).attr("data-node") == "crealbum") {
    $("#default-choice").text($(this).text());
    $("#add-new-album").html("<input type='text' id='new-album-name'></input><input type='submit' class='submit' value='Ok'></input>");
  }
  else {
    $("#add-new-album").html("");
     $("#add-new-album").html("<input type='submit' class='submit submit-alone' value='Ok'></input>");
  }
});

/* Disparition de la liste albums lors d'un clic extériieur */
$(".container-parent").on("click", function() {
  $("#option-list").fadeOut(150);
});

/* Enlever la photo participante */
$(".delete-pic").on("click", function() {
  var id = $(this).parent().attr("data-id");
  deletePicture(id);
});

$(document).on("click", ".photo", function() {
  alert("a");
  var id_pic = $(this).attr("data-id");

  if($(this).attr("data-like") == "true"){
    $.ajax({
      type:     'POST',
      url:      "../likeManager.php",
      data:     {"id_pic": id_pic, "liked": "false"}
    });
    $(this).attr("data-like", "false");
    $(this).siblings(".vote-zone").css("color", "white");
  } else {
    $.ajax({
      type:     'POST',
      url:      "../likeManager.php",
      data:     {"id_pic": id_pic, "liked": "false"}
    });
    $(this).attr("data-like", "true");
    $(this).siblings(".vote-zone").css("color", "green");
  }
});

/* $("#button-facebook-image").on("click", function() {
  $.getJSON()
}); */

/* Annuler la photo pré-upload */
function cancelUpload() {
  $("#upload-infos").remove();
  $("#dynamic-upload-zone").html('<div id="hide-button"><input type="file" name="browse-image" id="browse-image"></input></div><span id="button-browse-image" class="button-upload red-button">Je choisis une image de mon ordinateur...</span><br><span id="ou">OU</span><br><span id="button-facebook-image" class="button-upload red-button">Je choisis une image dans mes albums Facebook</span>');
}

/* Fonction de vérification de l'existence d'une image */
function isUrlExists(url, cb){
    try {
      $.ajax({
          url:       url,
          dataType:  'text',
          type:      'GET',
          complete:  function(xhr){
              if(typeof cb === 'function')
                 cb.apply(this, [xhr.status]);
          },
          error: function(){
            console.log(".");
          }
      });
    } catch(e) {
      console.log("Nope");
    }
}

function deletePicture(id) {
  $.ajax({
      type:   "POST",
      url:    "../deletePicture.php",
      data:   {"id_pic": id}, 
      complete: function() {
        top.location.href = '../contest.php';
      }
  }); 
}