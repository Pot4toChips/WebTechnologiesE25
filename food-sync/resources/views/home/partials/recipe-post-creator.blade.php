<div id="recipe-post-creator" class="content-card accordion w-100 mb-3 p-0">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Share your Recipes
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#recipe-post-creator">
            <div class="accordion-body">
                <form id="recipe-post-creator-form" method="POST" action="" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <label for="image" class="form-label">Thumbnail</label>
                    <div class="input-group mb-3">
                        <input id="recipe-image" class="form-control" type="file" name="image" accept="image/*" required>
                        <button id="clear-image" class="btn btn-outline-secondary" type="button">Clear</button>
                    </div>

                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredients</label>
                        <textarea type="text" class="form-control" name="ingredients" rows="6" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="instructions" class="form-label">Instructions</label>
                        <textarea type="text" class="form-control" name="instructions" rows="6" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 me-2">Share</button>
                    <button type="reset" class="btn btn-danger px-4">Clear</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #recipe-post-creator {
        max-width: 900px;
    }

    #recipe-post-creator .accordion-item,
    #recipe-post-creator .accordion-header,
    #recipe-post-creator .accordion-button,
    #recipe-post-creator .accordion-body {
        border: none;
    }

    #recipe-post-creator .accordion-button:not(.collapsed) {
        box-shadow: none;
    }
</style>

<script>
    const recipeImageInput = document.getElementById("recipe-image");
    const clearImageButton = document.getElementById("clear-image");
    clearImageButton.addEventListener("click", () => {
        recipeImageInput.value = "";
    });
</script>