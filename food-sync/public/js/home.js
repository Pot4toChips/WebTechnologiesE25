import { sendAPIRequest, storageURL, csrfToken } from "./scripts.js";

const recipePosts = document.getElementById("recipe-posts");
const recipePostCreatorForm = document.getElementById("recipe-post-creator-form");

recipePostCreatorForm.addEventListener("submit", (event) => {
    event.preventDefault();
    
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

    sendAPIRequest("recipe-posts/create-recipe-post", "POST", recipePostData);

    recipePostCreatorForm.reset();
});

function timeSince(dateStr) {
    let date = new Date(dateStr);
    let now = new Date();

    let seconds = Math.floor((now - date) / 1000);
    let minutes = Math.floor(seconds / 60);
    if (minutes < 60) {
        return `${minutes}m`;
    }

    let hours = Math.floor(minutes / 60);
    if (hours < 24) {
        return `${hours}h`;
    }

    let days = Math.floor(hours / 24);
    return `${days}d`;
}

function renderRecipePost(recipePostData) {
    let recipePost = document.createElement("article");
    recipePost.className = "recipe-post content-card d-flex flex-column align-items-start justify-content-start mb-3";
    recipePost.innerHTML = `
        <div class="recipe-post-header d-flex flex-row align-items-center justify-content-between m-0 w-100">
        <p class="m-0">${recipePostData.title}</p>
            <div class="d-flex flex-row align-items-center justify-content-center">
                <a class="text-secondary m-0">${recipePostData.author}</a>
                <p class="text-secondary m-0 mx-1">•</p>
                <p class="text-secondary m-0">${timeSince(recipePostData.time)}</p>
            </div>
        </div>
        <hr class="border-2 w-100 my-2">
        <div class="d-flex flex-row align-items-start justify-content-start my-2">
            <img class="rounded me-3" src="${storageURL}/recipe_post_images/${recipePostData.image}" alt="Image of ${recipePostData.title}">
            <div class="d-flex flex-column align-items-start justify-content-start h-100">
                <p class="m-0">Ingredients</p>
                <ul class="m-0">
                    ${recipePostData.ingredients.map(i => `<li>${i}</li>`).join("")}
                </ul>
            </div>
        </div>
        <hr class="border-2 w-100 my-2">
        <div class="d-flex flex-column align-items-start justify-content-start">
            <p class="m-0">Instructions</p>
            <ul class="m-0">
                ${recipePostData.instructions.map(i => `<li>${i}</li>`).join("")}
            </ul>
        </div>
    `
    return recipePost;
}

async function getRecipePosts() {
    let recipePostDatas = await sendAPIRequest("recipe-posts/get-recipe-posts", "GET");

    recipePostDatas.forEach(recipePostData => {
        let recipePost = renderRecipePost(recipePostData);
        recipePosts.appendChild(recipePost);
    });
}

getRecipePosts();