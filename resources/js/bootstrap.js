import axios from 'axios';
window.axios = axios;


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Intercept 419 "Page Expired" errors and reload the page to refresh the CSRF token.
 * This prevents the "Raw JSON" error when the session times out.
 */
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 419) {
            window.location.reload();
        }
        return Promise.reject(error);
    }
);
