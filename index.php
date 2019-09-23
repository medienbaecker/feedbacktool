<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>FeedbackTool</title>
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/autosize.min.js"></script>
    <script type="text/javascript" src="assets/js/f.js"></script>
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    
    <?php
      include('functions.php');
      
      // ID defined:
      if (
        isset($_GET["id"]) AND
        file_exists($_GET["id"] . '/image')
      ) {
        $folderName = $_GET["id"];
        $imagePath = $folderName . '/image';
      }
      
      // File uploaded:
      elseif (
        isset($_FILES['fileInput']) AND
        isset($_POST['imageData'])
      ) {
        $folderName = pathinfo($_FILES['fileInput']['name'], PATHINFO_FILENAME);
        $imagePath = $folderName . '/image';
        if (!file_exists($folderName)) mkdir($folderName);
        if (!file_exists($imagePath)) {
          file_put_contents($imagePath, $_POST['imageData']);
        }
      }
      
      // Feedbacks defined
      if(
        isset($folderName)
      ) {
        $feedbackPath = $folderName . "/feedbacks.json";
        if (isset($_POST["feedbacks"]) AND !empty($_POST["feedbacks"])) {
          file_put_contents($feedbackPath, urldecode($_POST["feedbacks"]));
        }
        elseif (!file_exists($feedbackPath) OR filesize($feedbackPath) == 0) {
          unset($feedbackPath);
        }
      }
      
    ?>
    
    <?php
      if(isset($folderName)) $action = "?id=" . $folderName;
      else $action = "";
    ?>
    
    <form action="<?= $action ?>" method="POST" id="fileUploadForm" enctype="multipart/form-data">
      
      <div class="input">
        <input type="file" id="fileInput" name="fileInput" accept="image/*" />
        <?php if(isset($imagePath)): ?>
        <label for="fileInput" id="customFileInput">Bild ändern</label>
        <?php else: ?>
        <label for="fileInput" id="customFileInput">Bild hochladen</label>
        <?php endif ?>
        
        <input type="hidden" id="imageData" name="imageData" />
        
        <input type="hidden" id="feedbacks" name="feedbacks" />
        
        <?php if(isset($imagePath)): ?>
        <button type="submit" class="submit">Speichern</button>
        <?php else: ?>
        <button type="submit" class="submit">Veröffentlichen</button>
        <?php endif ?>
      </div>
      
    </form>
    
    <div class="preview <?php if(isset($imagePath)): ?>visible<?php endif ?>">
      <div class="previewWrapper">
        <?php 
          if(isset($imagePath)): 
          $imageContent = file_get_contents($imagePath);
        ?>
        <img id="filePreview" src="<?= $imageContent ?>" />
        <?php else: ?>
        <img id="filePreview" src="" />
        <?php endif ?>
        
        <div id="fileOverlay">
          <?php 
            if(isset($feedbackPath)): 
            $feedbackContent = file_get_contents($feedbackPath);
            foreach(json_decode($feedbackContent) as $feedback):
          ?>
          	<div class="feedback visible" style="top: <?= $feedback->posY ?>px; left: <?= $feedback->posX ?>px">
              <i class="button"></i>
              <div class="comments">
                <?php
                  foreach ($feedback->comments as $comment): 
                ?>
                  <div class="comment-box">
                    <textarea><?= $comment ?></textarea>
                  </div>
                <?php
                  endforeach;
                ?>
              </div>
            </div>
          <?php 
            endforeach;
            endif;
          ?>
        </div>
        
      </div>
    </div>
    
  </body>
  </html>
</html>