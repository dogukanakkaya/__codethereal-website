import '../../js/common'

window.bootstrap = require('bootstrap')

window.addEventListener('DOMContentLoaded', e => {
    // Header element to make sticky
    const header = document.querySelector('header')
    // I get the top-bar height to add sticky class on scroll
    const topBarHeight = document.querySelector('.top-bar').clientHeight

    // Add sticky class if user scrolled under top-bar
    window.addEventListener('scroll', () => {
        window.pageYOffset >= topBarHeight ? header.classList.add('sticky') : header.classList.remove('sticky')
    })
})

window.__login = async () => {
    if (!document.getElementById('login-modal')){
        const {data: html} = await request.get(appUrl('_/auth/login'))
        document.body.insertAdjacentHTML('beforeend', html)
        document.getElementById('login-form').addEventListener('submit', async e => {
            e.preventDefault()
            const {data: { status, message }} = await request.post(appUrl('_/auth/login'), serialize(e.target, {hash: true, empty: true}))

            const alertEl = e.target.querySelector('.alert')
            if (status){
                e.target.reset()
                window.location.reload()
            }else{
                replaceClasses(alertEl, ['d-none', 'alert-success'], ['alert-danger'])
                alertEl.textContent = message
            }
        })
    }
    openModal('#login-modal')
}

window.__register = async () => {
    if (!document.getElementById('register-modal')){
        const {data: html} = await request.get(appUrl('_/auth/register'))
        document.body.insertAdjacentHTML('beforeend', html)
        document.getElementById('register-form').addEventListener('submit', async e => {
            e.preventDefault()
            const {data: { status, message }} = await request.post(appUrl('_/auth/register'), serialize(e.target, {hash: true, empty: true}))

            const alertEl = e.target.querySelector('.alert')
            if (status){
                replaceClasses(alertEl, ['d-none', 'alert-danger'], ['alert-success'])
                e.target.reset()
            }else{
                replaceClasses(alertEl, ['d-none', 'alert-success'], ['alert-danger'])
            }
            alertEl.innerHTML = message
        })
    }
    openModal('#register-modal')
}

window.__contact = async () => {
    if (!document.getElementById('contact-modal')){
        const {data: html} = await request.get(appUrl('_/contact'))
        document.body.insertAdjacentHTML('beforeend', html)
        document.getElementById('contact-form').addEventListener('submit', async e => {
            e.preventDefault()
            const {data: { status, message }} = await request.post(appUrl('_/contact'), serialize(e.target, {hash: true, empty: true}))

            const alertEl = e.target.querySelector('.alert')
            if (status){
                replaceClasses(alertEl, ['d-none', 'alert-danger'], ['alert-success'])
                e.target.reset()
            }else{
                replaceClasses(alertEl, ['d-none', 'alert-success'], ['alert-danger'])
            }
            alertEl.innerText = message
        })
    }
    openModal('#contact-modal')
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


import { createApp } from 'vue';
import Vote from './components/Vote'
import Search from './components/Search'
import SavePost from './components/SavePost'
const app = createApp({
    components: {
        Vote,
        Search,
        SavePost
    }
})

// Global functions for vue
app.config.globalProperties.register = __register

app.mount('#app')
