import { ceToast } from './toast'

window.serialize = require('form-serialize');
window.makeToast = ceToast

// Axios
window.request = axios.create({
    timeout: 30000,
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    validateStatus: function (status) {
        return status >= 200 && status <= 450;
    },
});

request.interceptors.response.use((response) => response, (error) => {
    makeToast({
        status: 0,
        title: 'Error',
        message: error.message
    })
    toggleBtnLoading()
});
