const recipePosts = document.getElementById("recipe-posts");

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

function createRecipePost(recipePostData) {
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
            <img class="rounded me-3" src="${recipePostData.image}" alt="Image of ${recipePostData.title}">
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

let recipePostDatas = [
    {
        title: "Chicken Alfredo",
        author: "@richardtivolt",
        time: "2025-10-04 23:11:00",
        image: "images/chicken_alfredo.png",
        ingredients: [
            "2 chicken breasts",
            "200g fettuccine pasta",
            "1 cup heavy cream",
            "1/2 cup grated Parmesan cheese",
            "2 tbsp butter",
            "2 cloves garlic, minced",
            "Salt and pepper to taste"
        ],
        instructions: [
            "Cook the fettuccine according to package instructions.",
            "Season chicken with salt and pepper, then cook in a skillet until golden and cooked through. Slice and set aside.",
            "In the same skillet, melt butter and sauté garlic until fragrant.",
            "Stir in the heavy cream and Parmesan cheese, then simmer until thickened.",
            "Add the pasta and chicken back into the sauce and toss to combine.",
            "Serve hot with extra Parmesan and parsley on top."
        ]
    },
    {
        title: "Beef Stir Fry",
        author: "@pauldonici",
        time: "2025-10-03 18:25:00",
        image: "images/beef_stir_fry.png",
        ingredients: [
            "400g beef sirloin, thinly sliced",
            "2 cups mixed vegetables (broccoli, bell pepper, carrots, snap peas)",
            "3 tbsp soy sauce",
            "2 tbsp oyster sauce",
            "1 tbsp cornstarch",
            "2 tbsp vegetable oil",
            "2 cloves garlic, minced",
            "1 tsp fresh ginger, grated",
            "1/4 cup water",
            "Cooked rice for serving"
        ],
        instructions: [
            "In a small bowl, mix soy sauce, oyster sauce, cornstarch, and water. Set aside.",
            "Heat oil in a wok or large skillet over medium-high heat.",
            "Add garlic and ginger; stir-fry until fragrant.",
            "Add the beef slices and cook until browned on all sides.",
            "Add the mixed vegetables and cook for 3-4 minutes until tender-crisp.",
            "Pour in the sauce mixture and stir until it thickens and coats the beef and veggies.",
            "Serve immediately over hot cooked rice."
        ]
    },
    {
        title: "Vegetarian Lasagna",
        author: "@hubageller",
        time: "2025-10-02 14:45:00",
        image: "images/vegetarian_lasagna.png",
        ingredients: [
            "9 lasagna noodles",
            "2 cups ricotta cheese",
            "2 cups mozzarella cheese, shredded",
            "1/2 cup Parmesan cheese, grated",
            "2 cups spinach, chopped",
            "2 cups marinara sauce",
            "1 zucchini, thinly sliced",
            "1 bell pepper, diced",
            "1 tbsp olive oil",
            "Salt and pepper to taste"
        ],
        instructions: [
            "Preheat oven to 375°F (190°C).",
            "Cook lasagna noodles according to package instructions. Drain and set aside.",
            "In a pan, heat olive oil and sauté zucchini and bell pepper until softened. Add spinach and cook until wilted.",
            "Spread a thin layer of marinara sauce in a baking dish.",
            "Layer noodles, ricotta mixture, vegetables, mozzarella, and sauce — repeat layers.",
            "Top with remaining mozzarella and Parmesan cheese.",
            "Bake for 30-35 minutes until bubbly and golden on top.",
            "Let rest 10 minutes before serving."
        ]
    },
    {
        title: "Shrimp Tacos with Lime Crema",
        author: "@romanteren",
        time: "2025-10-01 12:30:00",
        image: "images/shrimp_tacos.png",
        ingredients: [
            "400g shrimp, peeled and deveined",
            "8 small corn tortillas",
            "1 cup shredded cabbage",
            "1 avocado, sliced",
            "2 tbsp olive oil",
            "1 tsp chili powder",
            "1 tsp paprika",
            "1/2 tsp cumin",
            "Juice of 1 lime",
            "1/2 cup sour cream",
            "1 tbsp mayonnaise",
            "Salt and pepper to taste"
        ],
        instructions: [
            "In a bowl, toss shrimp with olive oil, chili powder, paprika, cumin, and a pinch of salt.",
            "Cook shrimp in a skillet over medium-high heat for 2-3 minutes per side until pink and opaque.",
            "In a small bowl, mix sour cream, mayonnaise, lime juice, and a pinch of salt to make the lime crema.",
            "Warm tortillas in a dry pan or microwave.",
            "Assemble tacos by layering cabbage, shrimp, avocado slices, and drizzling with lime crema.",
            "Serve immediately with lime wedges on the side."
        ]
    }
];

recipePostDatas.forEach(recipePostData => {
    let recipePost = createRecipePost(recipePostData);
    recipePosts.appendChild(recipePost);
});