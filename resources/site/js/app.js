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

document.querySelectorAll('.category-dropdown li').forEach(category => category.addEventListener('click', e => {
    document.querySelector('.category-dropdown .selected')?.classList.remove('selected'); // remove selected class
    e.target.classList.add('selected') // add selected class
    document.getElementById('selected').textContent = e.target.innerText // change selected text
    document.querySelector('.category-dropdown').classList.remove('visible', 'opacity-100') // close dropdown
    document.getElementById('search-category-id').value = e.target.value // set input value
}))

window.__login = async () => {
    if (!document.getElementById('login-modal')){
        const {data: html} = await request.get(url('auth/login'))
        document.body.insertAdjacentHTML('beforeend', html)
        document.getElementById('login-form').addEventListener('submit', async e => {
            e.preventDefault()
            const {data: { status, message }} = await request.post(url('auth/login'), serialize(e.target, {hash: true, empty: true}))

            const alertEl = e.target.querySelector('.alert')
            if (status){
                replaceClasses(alertEl, ['d-none', 'alert-danger'], ['alert-success'])
                e.target.reset()
                setTimeout(() => {
                    window.location.reload()
                }, 1500)
            }else{
                replaceClasses(alertEl, ['d-none', 'alert-success'], ['alert-danger'])
            }
            alertEl.textContent = message
        })
    }
    openModal('#login-modal')
}

window.__register = async () => {
    if (!document.getElementById('register-modal')){
        const {data: html} = await request.get(url('auth/register'))
        document.body.insertAdjacentHTML('beforeend', html)
        document.getElementById('register-form').addEventListener('submit', async e => {
            e.preventDefault()
            const {data: { status, message }} = await request.post(url('auth/register'), serialize(e.target, {hash: true, empty: true}))

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

document.addEventListener('click', e => {
    const clickedMenu = document.querySelector('.menus').contains(e.target);
    const clickedToggle = document.querySelector('label[for="menu-toggle"]').contains(e.target);
    const clickedToggleInput = document.getElementById('menu-toggle').contains(e.target);

    // If user is not clicked mobile menu, label toggler, checkbox input it means clicked outside of these three element. Just close the mobile menu.
    if (!clickedMenu && !clickedToggle && !clickedToggleInput) {
        document.getElementById('menu-toggle').checked = false
    }
});
