import { sendAPIRequest, storageURL } from "./scripts.js";

document.addEventListener("DOMContentLoaded", async () => {
    await setupSettingsPage();

    const item = document.querySelector(".setting-item");
    if (item) item.click(); // SELECT THE FIRST MENU TIEM

    localStorage.getItem('theme') ? document.getElementById(`theme-${localStorage.getItem("theme").toLowerCase()}-radio`).checked = true : "";
});

async function setupSettingsPage() {
    const root = document.getElementById("settings") || document.body;

    try {
        const userData = await sendAPIRequest("user", "GET"); // Get userdata

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
                                    <small>${userData.name}</small>
                                </div>
                                <button class="btn btn-primary change-name-btn">Change</button>
                            </div>

                            <div class="mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 fw-semibold">Email</p>
                                    <small>${userData.email}</small>
                                </div>
                            </div>

                           <div class="mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 fw-semibold">Password</p>
                                    <small>********</small>
                                </div>
                                <button class="btn btn-primary change-password-btn">Change</button>
                            </div>

                            <div class="pt-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 fw-semibold text-danger">Delete Account</p>
                                    <small>This action is permanent and irreversible.</small>
                                </div>
                                <button class="btn btn-danger delete-user-btn">Delete</button>
                            </div>
                        </div>
                </div>`,

            "preferences":
                `<div class="d-flex flex-column align-items-center justify-content-start overflow-auto w-100 px-4 pt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 w-50 py-2 border-bottom">

                            <label class="form-label fw-semibold mb-0">Theme</label>

                            <input type="radio" name="theme" id="theme-system-radio" hidden checked>
                            <input type="radio" name="theme" id="theme-light-radio" hidden>
                            <input type="radio" name="theme" id="theme-dark-radio" hidden>

                            <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-theme-btn">
                                    ${activeTheme}
                                </button>

                                <ul class="dropdown-menu w-25">
                                    <li><a class="dropdown-item theme-option" href="#" value="Light">Light</a></li>
                                    <li><a class="dropdown-item theme-option" href="#" value="Dark">Dark</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 w-50 py-2 border-bottom">
                            <label class="form-label fw-semibold">Language</label>
                            <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-language-btn">
                                    English
                                </button>
                                <ul class="dropdown-menu w-25">
                                    <li><a class="dropdown-item languages-option" href="#" data-language="English">English</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center w-50 py-2 border-bottom mb-3">
                            <label class="form-label fw-semibold">Ratings</label>
                            <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-rating-btn">
                                    Show
                                </button>
                                <ul class="dropdown-menu w-25">
                                    <li><a class="dropdown-item ratings-option" href="#" data-rating="Show">Show</a></li>
                                    <li><a class="dropdown-item ratings-option" href="#" data-rating="Hide">Hide</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center w-50 py-2 border-bottom mb-3">
                            <label class="form-label fw-semibold">Comments</label>
                            <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-comment-btn">
                                    Show
                                </button>
                                <ul class="dropdown-menu w-25">
                                    <li><a class="dropdown-item comments-option" href="#" data-comment="Show">Show</a></li>
                                    <li><a class="dropdown-item comments-option" href="#" data-comment="Hide">Hide</a></li>
                                </ul>
                            </div>
                        </div>
                </div>`,

            "feedback":
                `<div class="d-flex flex-column align-items-center justify-content-start overflow-auto w-100 px-4 pt-4">
                        <h5>We'd love to hear your feedback on our service. Please let us know how we can improve them.</h5>
                        <div class="w-50 mt-3">
                            <label for="feedback-email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="feedback-email" placeholder="name@example.com" value="${userData.email}">
                        </div>

                        <div class="mb-3 w-50 mt-3">
                            <label for="feedback-form" class="form-label">Feedback</label>
                            <textarea class="form-control" id="feedback-form" rows="3"></textarea>
                        </div>

                        <button type="button" class="btn btn-primary submit-feedback-btn">Submit</button>
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

        // Modals
        const modals = `
            <div class="modal fade" id="changeNameModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change Name</h4>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="newNameInput" class="form-label">Name</label>
                            <input id="newNameInput" type="text" class="form-control" placeholder="Enter your new name" value="${userData.name}">
                        </div>
                        <div class="modal-footer">
                            <button id="saveNameBtn" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="changePasswordModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change Password</h4>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="currentPassword" class="form-label">Old Password</label>
                            <input id="currentPassword" type="password" class="form-control mb-2" placeholder="Enter your current password">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input id="newPassword" type="password" class="form-control mb-2" placeholder="Enter your new password">
                            <label for="newPasswordConfirm" class="form-label">Confirm New Password</label>
                            <input id="newPasswordConfirm" type="password" class="form-control" placeholder="Confirm your new password">
                        </div>
                        <div class="modal-footer">
                            <button id="savePasswordBtn" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteUserModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Are you sure you want to delete your profile?</h4>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="currentPasswordForDelete" class="form-label">Password</label>
                            <input id="currentPasswordForDelete" type="password" class="form-control mb-2" placeholder="Enter your password">
                        </div>
                        <div class="modal-footer">
                            <button id="deleteUserBtn" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML("beforeend", modals);



        document.getElementById("saveNameBtn").addEventListener("click", handleNameChange);
        document.getElementById("savePasswordBtn").addEventListener("click", handlePasswordChange);
        document.getElementById("deleteUserBtn").addEventListener("click", handleDeleteUser);

        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("change-name-btn")) {
                const modal = new bootstrap.Modal(document.getElementById('changeNameModal'));
                modal.show();
            }

            if (e.target.classList.contains("change-password-btn")) {
                const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
                modal.show();
            }

            if (e.target.classList.contains("delete-user-btn")) {
                const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                modal.show();
            }

            if (e.target.classList.contains("submit-feedback-btn")) {
                handleFeedbackSubmit();
            }

            if (e.target.classList.contains("theme-option")) {
                e.preventDefault();
                setTheme(e.target.getAttribute("value"));
            }

            if (e.target.classList.contains("units-option")) {
                e.preventDefault();
                setUnits(e.target.dataset.units);
            }

            if (e.target.classList.contains("visibility-option")) {
                e.preventDefault();
                setProfileVisibility(e.target.dataset.visibility);
            }

            if (e.target.classList.contains("comment-option")) {
                e.preventDefault();
                setComment(e.target.dataset.comment);
            }
        });

        root.appendChild(main);

    } catch (error) {
        console.error("Error setting up settings page:", error);
        alert("Error loading settings. Please try again.");
    }
}

async function handleNameChange() {
    const newName = document.getElementById("newNameInput").value.trim();

    if (!newName) {
        alert("Please enter a name");
        return;
    }

    try {
        const response = await sendAPIRequest("change-name", "POST", {
            name: newName
        });

        if (response.error) {
            alert(response.error);
            return;
        }

        alert("Name updated successfully!");
        const modal = bootstrap.Modal.getInstance(document.getElementById('changeNameModal'));
        modal.hide();
        document.querySelector('#account .text-muted').textContent = newName;
    } catch (error) {
        alert("Error updating name. Please try again.");
    }
}

async function handlePasswordChange() {
    const currentPassword = document.getElementById("currentPassword").value;
    const newPassword = document.getElementById("newPassword").value;
    const newPasswordConfirm = document.getElementById("newPasswordConfirm").value;

    if (!currentPassword || !newPassword || !newPasswordConfirm) {
        alert("No password field should be empty.");
        return;
    }

    if (newPassword !== newPasswordConfirm) {
        alert("New passwords do not match.");
        return;
    }

    try {
        const response = await sendAPIRequest("change-password", "POST", {
            current_password: currentPassword,
            new_password: newPassword,
            new_password_confirmation: newPasswordConfirm,
        });

        if (response.error) {
            alert(response.error);
            return;
        }

        alert("Password updated successfully!");
        const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
        modal.hide();

        document.getElementById("currentPassword").value = '';
        document.getElementById("newPassword").value = '';
        document.getElementById("newPasswordConfirm").value = '';
    } catch (error) {
        alert("Error updating password. Please try again.");
    }
}

async function handleDeleteUser() {
    const currentPassword = document.getElementById("currentPasswordForDelete").value;

    if (!currentPassword) {
        alert("Password field should not be empty.");
        return;
    }

    try {
        const response = await sendAPIRequest("delete-user", "POST", {
            password: currentPassword
        });

        if (response.error) {
            alert(response.error);
            return;
        }

        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
        modal.hide();

        document.getElementById("currentPassword").value = '';


    } catch (error) {
        alert("Error deleting user. Please try again.");
    } finally {
        location.replace("http://127.0.0.1:8000/home"); /////
    }
}

async function handleFeedbackSubmit() {
    const email = document.getElementById("feedback-email").value.trim();
    const feedback = document.getElementById("feedback-form").value.trim();

    if (!feedback) {
        alert("Please enter your feedback");
        return;
    }

    if (!email) {
        alert("Please enter your email address");
        return;
    }

    try {
        const response = await sendAPIRequest("feedback", "POST", {
            email: email,
            feedback: feedback
        });

        if (response.error) {
            alert(response.error);
            return;
        }

        alert("Thank you for your feedback!");
        document.getElementById("feedback-form").value = '';
    } catch (error) {
        alert("Error submitting feedback. Please try again.");
    }
}

let activeTheme = localStorage.getItem('theme') || 'Light';

function setTheme(theme) {
    activeTheme = theme;
    localStorage.setItem('theme', theme)
    document.getElementById(`theme-${theme.toLowerCase()}-radio`).checked = true;
    document.querySelector(".dropdown-theme-btn").textContent = theme;
}

function setUnits(units) {
    document.querySelector('.dropdown-units-btn').textContent = units;
}