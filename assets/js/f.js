$(document).ready(function() {
  
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#filePreview').attr('src', e.target.result);
        $('#imageData').val(e.target.result);
        $('#fileUploadForm').attr('action', "?id=" + input.files[0].name.replace(/\.[^/.]+$/, ""));
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  
  function addFeedback(posX, posY) {
    var num = $("#fileOverlay .feedback").length,
        feedback = $('<div class="feedback" style="top: ' + posY + 'px; left: ' + posX + 'px"></div>');
    $("#fileOverlay").append(feedback);
    feedback.append('<i class="button"></i>');
    feedback.append('<div class="comments"><div class="comment-box"><textarea></textarea></div></div>');
    setTimeout(function() {
      feedback.addClass("visible");          
    }, 10);
    setTimeout(function() {
    	feedback.addClass("open");
    	feedback.find("textarea").focus();
    	autosize(feedback.find("textarea"));
    }, 300);
  }
  
  function saveFeedbacks() {
    var feedbacks = [];
    $("#fileOverlay .feedback").each(function() {

      var comments = [];
      $(this).find(".comments textarea").each(function() {
        comments.push($(this).val());
      });
      var feedback = {};
      feedback.posX = $(this).css("left").replace("px", "");
      feedback.posY = $(this).css("top").replace("px", "");
      feedback.comments = comments;
      
      feedbacks.push(feedback);
    });
    $("#feedbacks").val(encodeURIComponent(JSON.stringify(feedbacks)));
  }
  
  $("#fileInput").change(function() {
    readURL(this);
    $('.preview').addClass("visible");
  });
  
  $('.preview').on("mousedown", ".previewWrapper", function(e) {
    if (e.which === 1 && $(e.target).closest(".feedback").length === 0) {
            
      $(".preview #fileOverlay .feedback").each(function() {
        empty = true;
        $(this).find("textarea").each(function() {
          if ($.trim($(this).val())) {
            empty = false;
          }
        });
        if (empty) {
          $(this).remove();
        }
      });
      
      
      $(".feedback").removeClass("open");
      var posX = e.pageX - $(".preview.visible .previewWrapper").position().left,
          posY = e.pageY - $(".preview.visible .previewWrapper").position().top;
      
      addFeedback(posX, posY);
    }
  });
  
  $('.preview').on("keyup", "textarea", function(e) {
    saveFeedbacks();
  });
  
  $('.preview').on("click", ".feedback.visible .button", function(e) {
  	var feedback = $(this).closest(".feedback");
  	if (feedback.hasClass("open")) {
      
      empty = true;
      feedback.find("textarea").each(function() {
        if ($.trim($(this).val())) {
          empty = false;
        }
      });
      if (empty) {
        feedback.remove();
      }
      else {
        feedback.removeClass("open");
      }
  	}
  	else {
  	  $(".feedback").removeClass("open");
  	  feedback.addClass("open");
  	  feedback.find("textarea").focus();
  	}
  });
  
});