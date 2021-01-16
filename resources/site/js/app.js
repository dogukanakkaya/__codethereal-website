import axios from 'axios'
window.bootstrap = require('bootstrap')

window.toggleMultiple = (el, ...classes) => classes.map(cls => el.classList.toggle(cls))

window.addEventListener('DOMContentLoaded', e => {

    // Header element to make sticky
    const header = document.querySelector('header')
    // I get the top-bar height to add sticky class on scroll
    const topBarHeight = document.querySelector('.top-bar').clientHeight

    // Add sticky class if user scrolled under top-bar
    window.addEventListener('scroll', e => {
        window.pageYOffset >= topBarHeight ? header.classList.add('sticky') : header.classList.remove('sticky')
    })
})

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

window.__login = async () => {
    if (!document.getElementById('login-modal')){
        const response = await axios.get(url('auth/login'))
        document.body.insertAdjacentHTML('beforeend', response.data)
    }
    openModal('#login-modal')
}

window.__register = async () => {
    if (!document.getElementById('register-modal')){
        const response = await axios.get(url('auth/register'))
        document.body.insertAdjacentHTML('beforeend', response.data)
    }
    openModal('#register-modal')
}

document.addEventListener('click', e => {
    const clickedMenu = document.querySelector('.menus').contains(e.target);
    const clickedToggle = document.querySelector('label[for="menu-toggle"]').contains(e.target);
    const clickedToggleInput = document.getElementById('menu-toggle').contains(e.target);

    // If user is not clicked mobile menu, label toggler, checkbox input it means clicked outside of these three element. Just close the mobile menu.
    if (!clickedMenu && !clickedToggle && !clickedToggleInput) {
        document.getElementById('menu-toggle').checked = false
    }
});
