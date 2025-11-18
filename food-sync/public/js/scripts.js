const screenBlocker = document.getElementById("screen-blocker");
const toastMessage = document.getElementById('toast-message');
const toastText = document.getElementById("toast-text");
const toast = new bootstrap.Toast(toastMessage);

export const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
export const storageURL = "https://vvtmkzsrflnaqphsxxal.supabase.co/storage/v1/object/public";

export async function sendAPIRequest(path, method, data = null) {
    let options = { method };

    if (data instanceof FormData) {
        options.body = data;
        options.headers = {};
    }
    else if (data !== null) {
        options.headers = {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        };
        options.body = JSON.stringify(data);
    }

    try {
        let response = await fetch(`/api/${path}`, options);

        if (!response.ok) {
            console.error(`API error: ${response.status} ${response.statusText}`);
            return null;
        }
        return await response.json();
    }
    catch (error) {
        console.error('Network error:', error);
        return null;
    }
}

export function timeSince(dateString) {
    let date = new Date(dateString.split('+')[0]); // Remove +00 at the end -> It tricked the date into thinking it is utc+0 instead of +1 cet
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

export async function executeAsyncSafe(action, errorMessage = ""){
    showScreenBlocker();

    try {
        await action();
    } 
    catch (e) {
        console.error(e);

        showToastError(errorMessage);
    }

    hideScreenBlocker();
}

export function showScreenBlocker() {
    screenBlocker.classList.remove("d-none");
    screenBlocker.classList.add("d-flex");
}

export function hideScreenBlocker() {
    screenBlocker.classList.remove("d-flex");
    screenBlocker.classList.add("d-none");
}

export function showToastMessage(text){
    toastText.textContent = text;
    toastMessage.classList.add("bg-success");
    toastMessage.classList.remove("bg-danger");
    toast.show();
}

export function showToastError(text){
    toastText.textContent = text;
    toastMessage.classList.add("bg-danger");
    toastMessage.classList.remove("bg-success");
    toast.show();
}

function theme() {
    let theme = localStorage.getItem('theme') || 'Light';
    if (theme === "Dark") {
        document.documentElement.classList.add("dark")
    } else {
        document.documentElement.classList.remove("dark");
    }
}

theme();