document.addEventListener("DOMContentLoaded", setupSettingsPage);

function setupSettingsPage() {
    const root = document.getElementById("settings") || document.body;

    const main = document.createElement("main");
    main.className = "content d-flex flex-column align-items-center justify-content-start overflow-x-hidden overflow-y-hidden col-10 m-0 p-0";

    const wrapper = document.createElement("div");
    wrapper.className = "d-flex flex-column align-items-center justify-content-start overflow-auto w-100 px-4 pt-4";
    main.appendChild(wrapper);

    const title = document.createElement("h1");
    title.className = "mb-4";
    title.textContent = "Settings";
    wrapper.appendChild(title);

    const menu = document.createElement("div");
    menu.id = "settings-menu";
    menu.className = "d-flex flex-row justify-content-center align-items-center w-50 gap-5 mx-auto";
    wrapper.appendChild(menu);

    const menuItems = [
        { name: "Account", id: "account" },
        { name: "Preferences", id: "preferences" },
        { name: "Privacy & Security", id: "privacy-security" },
        { name: "Send Feedback", id: "feedback" },
    ];

    menuItems.forEach(item => {
        const div = document.createElement("div");
        div.className = "setting-item";
        div.dataset.target = item.id;
        div.innerHTML = `
            <p class="mb-0">${item.name}</p>
        `;
        menu.appendChild(div);
    });

    const content = document.createElement("div");
    content.id = "settings-content";
    content.className = "w-75 mt-4";
    wrapper.appendChild(content);

    const sections = {
        "account": 
            `<div class="d-flex flex-column align-items-center justify-content-start overflow-auto w-100 px-4 pt-4">

                    <div class="w-50">

                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">
                            <div>
                            <p class="mb-1 fw-semibold">Name</p>
                            <small class="text-muted">John Doe</small>
                            </div>
                            <button>Edit</button>
                        </div>

                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">
                            <div>
                            <p class="mb-1 fw-semibold">Email</p>
                            <small class="text-muted">user@example.com</small>
                            </div>
                        </div>

                        <div class="pt-3 d-flex justify-content-between align-items-center">
                            <div>
                            <p class="mb-1 fw-semibold text-danger">Delete Account</p>
                            <small class="text-muted">Permanently remove your account and all data</small>
                            </div>
                            <button class="delete-button">Delete</button>
                        </div>

                    </div>
            </div>`,

        "preferences": 
            `<div class="d-flex flex-column align-items-center justify-content-start overflow-auto w-100 px-4 pt-4">
                    

                    <div class="d-flex justify-content-between align-items-center mb-3 w-50 py-2 border-bottom">
                        <label for="dropdown-theme" class="form-label fw-semibold mb-0">Theme</label>
                        
                        <div class="dropdown">
                            <button
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    id="dropdown-theme">
                            Light
                            </button>
                            <ul class="dropdown-menu w-25"  aria-labelledby="dropdown-theme">
                            <li><a class="dropdown-item" href="#" onclick="setTheme('Light')">Light</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setTheme('Dark')">Dark</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 w-50 py-2 border-bottom">
                        <label for="dropdown-units" class="form-label fw-semibold">Measurement Units</label>
                        <div class="dropdown">
                            <button 
                                    type="button" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false" 
                                    id="dropdown-units">
                            Imperial
                            </button>
                            <ul class="dropdown-menu w-25" aria-labelledby="dropdown-units">
                            <li><a class="dropdown-item" href="#" onclick="setUnits('Imperial')">Imperial</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setUnits('Metric')">Metric</a></li>
                            </ul>
                        </div>
                    </div>
                    
            </div>`,

        "privacy-security": 
            `<div class="d-flex flex-column align-items-center justify-content-start overflow-auto w-100 px-4 pt-4">

                    <div class="d-flex justify-content-between align-items-center w-50 py-2 border-bottom mb-3">
                        <label for="dropdown-profile-visibility" class="form-label fw-semibold mb-0">Profile Visibility</label>
                        
                        <div class="dropdown">
                            <button
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    id="dropdown-profile-visibility">
                            Public
                            </button>
                            <ul class="dropdown-menu w-25"  aria-labelledby="dropdown-profile-visibility">
                            <li><a class="dropdown-item" href="#" onclick="setProfileVisibility('Public')">Public</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setProfileVisibility('Followers')">Followers</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setProfileVisibility('Private')">Private</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center w-50 py-2 border-bottom mb-3">
                        <label for="dropdown-comment" class="form-label fw-semibold">Who can comment?</label>
                        <div class="dropdown">
                            <button 
                                    type="button" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false" 
                                    id="dropdown-comment">
                            Everyone
                            </button>
                            <ul class="dropdown-menu w-25" aria-labelledby="dropdown-comment">
                            <li><a class="dropdown-item" href="#" onclick="setComment('Everyone')">Everyone</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setComment('Followers')">Followers</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setComment('Noone')">Noone</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="pt-3 w-50">
                            <div>
                            <p class="mb-1 fw-semibold">Blocked Users</p>
                            <small class="text-muted">food-lover-69</small>
                            </div>
                    </div>
                    
                </div>`,

        "feedback": 
            `<div class="d-flex flex-column align-items-center justify-content-start overflow-auto w-100 px-4 pt-4">
                    
                    <h5>We'd love to hear your feedback on our service. Please let us know how we can improve them.</h3>
                    <div class="w-50 mt-3">
                        <label for="feedback-email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="feedback-email" placeholder="name@example.com">
                    </div>
                        
                    <div class="mb-3 w-50 mt-3">
                        <label for="feedback-form" class="form-label">Feedback</label>
                        <textarea class="form-control" id="feedback-form" rows="3"></textarea>
                    </div>

                    <button type="button" class="btn-lg">Submit</button>
                    
            </div>`
    };

    for (const [id, html] of Object.entries(sections)) {
        const section = document.createElement("div");
        section.id = id;
        section.className = "setting-section d-none";
        section.innerHTML = html;
        content.appendChild(section);
    }

    menu.querySelectorAll(".setting-item").forEach(item => {
        item.addEventListener("click", () => {
            content.querySelectorAll(".setting-section").forEach(s => s.classList.add("d-none"));
            menu.querySelectorAll(".setting-item").forEach(i => i.classList.remove("active"));

            const target = item.dataset.target;
            document.getElementById(target).classList.remove("d-none");
            item.classList.add("active");
        });
    });

    root.appendChild(main);
}
