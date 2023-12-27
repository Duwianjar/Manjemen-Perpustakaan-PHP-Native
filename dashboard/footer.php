<div class="py-6 px-6 text-center">
    <p class="mb-0 fs-4">Design and Developed by <a href="https://duwianjar.github.io/react-daaw" target="_blank"
            class="pe-1 text-primary text-decoration-underline">Duwiaaw</a></p>
</div>
</div>
</div>
</div>
<script src="../..assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/sidebarmenu.js"></script>
<script src="../../assets/js/app.min.js"></script>
<script src="../../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
<script src="../../assets/libs/simplebar/dist/simplebar.js"></script>
<script src="../../assets/js/dashboard.js"></script>
<script>
// Function to display the selected photo
function previewPhoto(input) {
    var previewContainer = document.getElementById('preview-container');
    var preview = document.getElementById('preview');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Attach the previewPhoto function to the change event of the photo input
document.getElementById('photo').addEventListener('change', function() {
    previewPhoto(this);
});

// Function to display the selected photo
function previewImage(input) {
    var imagePreviewContainer = input.parentElement.querySelector('.image-preview-container');
    var imagePreview = imagePreviewContainer.querySelector('.image-preview');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>

</html>