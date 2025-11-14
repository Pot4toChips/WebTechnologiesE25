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
            console.error(response.json());
            return null;
        }
        return await response.json();
    }
    catch (error) {
        console.error('Network error:', error);
        return null;
    }
}