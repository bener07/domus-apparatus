<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Party Image Gallery with Bootstrap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <!-- Main Image Display -->
      <div class="col-12 col-md-8 mb-4">
        <div class="border" id="main-image-container">
          <img id="mainImage" src="image1.jpg" alt="Party Image" class="img-fluid">
        </div>
      </div>

      <!-- Thumbnails -->
      <div class="col-12">
        <div class="row">
          <div class="col-3 col-md-2 mb-3">
            <img src="image1.jpg" alt="Thumbnail 1" class="img-thumbnail" onclick="changeImage('image1.jpg')">
          </div>
          <div class="col-3 col-md-2 mb-3">
            <img src="image2.jpg" alt="Thumbnail 2" class="img-thumbnail" onclick="changeImage('image2.jpg')">
          </div>
          <div class="col-3 col-md-2 mb-3">
            <img src="image3.jpg" alt="Thumbnail 3" class="img-thumbnail" onclick="changeImage('image3.jpg')">
          </div>
          <div class="col-3 col-md-2 mb-3">
            <img src="image4.jpg" alt="Thumbnail 4" class="img-thumbnail" onclick="changeImage('image4.jpg')">
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function changeImage(imageSrc) {
  document.getElementById('mainImage').src = imageSrc;
}
  </script>
</body>
</html>
