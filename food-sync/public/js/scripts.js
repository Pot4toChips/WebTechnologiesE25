const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export const storageURL = "https://vvtmkzsrflnaqphsxxal.supabase.co/storage/v1/object/public";

export async function sendAPIRequest(path, method, data = null) {
    try {
        let response = await fetch(`/api/${path}`, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken,
            },
            body: data ? (data instanceof FormData ? data : JSON.stringify(data)) : null
        });

        if (!response.ok) {
            console.error(`API error: ${response.status} ${response.statusText}`);
            return null;
        }
        return await response.json();
    } catch (error) {
        console.error('Network error:', error);
        return null;
    }
}