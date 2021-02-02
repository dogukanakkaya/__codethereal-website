import axios from 'axios'

// TODO: i needed to modify this to return 0 on empty checkbox so i copy core files and change the line
window.serialize = require('./static/form-serialize');

window.replaceClasses = (el, search, replace) => {
    el.classList.remove(...search)
    el.classList.add(...replace)
}

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

/* Opening and closing modal with bs5 */
window.openModal = selector => {
    const modalEl = document.querySelector(selector)
    const modalInstance = bootstrap.Modal.getInstance(modalEl)
    if (modalInstance){
        modalInstance.show()
    }else{
        new bootstrap.Modal(document.querySelector(selector)).show()
    }
}
window.closeModal = selector => {
    const modalEl = document.querySelector(selector)
    const modalInstance = bootstrap.Modal.getInstance(modalEl)
    if (modalInstance){
        modalInstance.hide()
    }else{
        new bootstrap.Modal(document.querySelector(selector)).hide()
    }
}
/* /Opening and closing modal with bs5 */

window.changeModalTitle = (selector, title) => document.querySelector(selector).querySelector('.modal-title').innerText = title
