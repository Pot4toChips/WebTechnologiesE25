import { sendAPIRequest, timeSince, storageURL, csrfToken, executeAsyncSafe, showToastMessage, theme } from "./scripts.js";

theme();

const recipePosts = document.getElementById("recipe-posts");
const recipePostTemplate = document.getElementById("recipe-post-template");
const recipePostPlaceholder = document.getElementById("recipe-post-placeholder");
const recipePostCreatorForm = document.getElementById("recipe-post-creator-form");
recipePostCreatorForm.addEventListener("submit", async function (event) {
    event.preventDefault();

    executeAsyncSafe(createRecipePost, "Error while posting the recipe.");
});

function renderRecipePost(recipePostData) {
    let recipePost = document.createElement("article");
    let templateHTML = recipePostTemplate.innerHTML;

    let ingredientsHTML = recipePostData.ingredients.map(i => `<li>${i}</li>`).join("");
    let instructionsHTML = recipePostData.instructions.map(i => `<li>${i}</li>`).join("")

    // Without a regex, it only replaces the first occurrence
    let recipePostHTML = templateHTML
        .replace(/\[TITLE\]/g, recipePostData.title)
        .replace(/\[AUTHOR\]/g, recipePostData.author)
        .replace(/\[TIME\]/g, timeSince(recipePostData.time))
        .replace(/\[IMAGE_URL\]/g, `${storageURL}/recipe_post_images/${recipePostData.image}`)
        .replace(/\[INGREDIENTS\]/g, ingredientsHTML)
        .replace(/\[INSTRUCTIONS\]/g, instructionsHTML);
    recipePost.innerHTML = recipePostHTML;

    return recipePost;
}

async function getRecipePosts() {
    let recipePostDatas = await sendAPIRequest("recipe-posts/get-recipe-posts", "GET");

    recipePostDatas.forEach(recipePostData => {
        try {
            let recipePost = renderRecipePost(recipePostData);
            recipePosts.appendChild(recipePost);
        } catch (e) {
            console.error(e);
        }
    });

    recipePostPlaceholder.classList.add("d-none");
}

async function createRecipePost() {
    let recipePostData = new FormData();

    recipePostData.append("_token", csrfToken);
    recipePostData.append("title", recipePostCreatorForm.querySelector("[name=title]").value);
    recipePostData.append("image", recipePostCreatorForm.querySelector("[name=image]").files[0]);
    recipePostData.append("ingredients", JSON.stringify(
        recipePostCreatorForm.querySelector("[name=ingredients]").value.split('\n')
    ));
    recipePostData.append("instructions", JSON.stringify(
        recipePostCreatorForm.querySelector("[name=instructions]").value.split('\n')
    ));

    recipePostCreatorForm.reset();

    await sendAPIRequest("recipe-posts/create-recipe-post", "POST", recipePostData);

    showToastMessage("Recipe posted successfully!");
}

executeAsyncSafe(getRecipePosts, "Error while fetching the recipes.");