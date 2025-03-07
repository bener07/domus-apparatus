<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center">Create Product</h2>
        <form>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="details" class="form-label">Details</label>
                        <textarea class="form-control" id="details" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-select" id="tags">
                            <option value="tag1">Tag 1</option>
                            <option value="tag2">Tag 2</option>
                            <option value="tag3">Tag 3</option>
                            <option value="tag4">Tag 4</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" min="1" required>
                    </div>
                </div>
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <label class="form-label">Main Photo</label>
                    <input type="file" class="form-control" id="mainPhoto" accept="image/*" onchange="previewImage(event, 'mainPreview')">
                    <img id="mainPreview" class="img-thumbnail mt-2 d-none" width="250">
                    
                    <label class="form-label mt-3">Secondary Photos</label>
                    <input type="file" class="form-control" id="secondaryPhotos" accept="image/*" multiple onchange="previewMultipleImages(event, 'secondaryPreviews')">
                    <div id="secondaryPreviews" class="d-flex flex-wrap mt-2"></div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event, previewId) {
        const preview = document.getElementById(previewId);
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    function previewMultipleImages(event, previewContainerId) {
        let imageCounter = 0;
        const container = document.getElementById(previewContainerId);
        container.innerHTML = ''; // Clear previous images
        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create a hidden input for each image
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.style.display = 'none'; // Hide the input
                input.id = `image-input-${imageCounter}`;
                container.appendChild(input);

                // Create an image element
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'm-1');
                img.id = `image-${imageCounter}`;
                img.style.width = '100px';

                // Attach the click event to the image
                img.addEventListener('click', function() {
                    input.click();  // Trigger the file input when the image is clicked
                });

                // Add event listener to the input to update the image when a new file is selected
                input.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            img.src = e.target.result;  // Update the image when a new file is selected
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Append the image to the container
                container.appendChild(img);

                // Increment the counter for the next image
                imageCounter++;
            };
            reader.readAsDataURL(file);
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/vendor/jquery/jquery.min.js"></script>
</body>
</html>
