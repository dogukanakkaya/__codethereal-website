// TODO: check all window object definitions imported including
import './bootstrap'
import 'datatables.net'
import ceToast from './ce/toast'
import { STORAGE_PREFIX } from "./constants";

// TODO: i needed to modify this to return 0 on empty checkbox so i copy core files and change the line
window.serialize = require('./static/form-serialize');
window.makeToast = ceToast

/* Global functions */
const setHtmlTheme = theme => document.querySelector('html').setAttribute('theme', theme);
const withZeros = time => time < 10 ? "0" + time : time;

window.addEventListener('DOMContentLoaded', () => {
    // If user is in tablet just close menu at start
    const mediaQuery = window.matchMedia('(max-width: 767px)')
    if (mediaQuery.matches) {
        document.getElementById('menu-toggle').removeAttribute('checked')
    }

    const liveTimeEl = document.querySelector('.live-time span');
    setInterval(() => {
        const d = new Date();
        liveTimeEl.textContent = withZeros(d.getHours()) + ":" + withZeros(d.getMinutes()) + ":" + withZeros(d.getSeconds());
    }, 1000);
})

window.addEventListener('load', () => {
    // Remove loader
    document.getElementById('loader').remove()

    const theme = localStorage.getItem(`${STORAGE_PREFIX}_theme`) || 'light-theme'
    toggleTheme(theme)
})

// Set theme
window.toggleTheme = (theme = null) => {
    const activeTheme = localStorage.getItem(`${STORAGE_PREFIX}_theme`)
    if (!theme){
        if (activeTheme === 'light-theme'){
            toggleTheme('dark-theme')
        }else{
            toggleTheme('light-theme')
        }
    }else{
        localStorage.setItem(`${STORAGE_PREFIX}_theme`, theme)
        setHtmlTheme(theme)
    }
}

// Open the theme settings sidebar
window.toggleThemeSettings = () => {
    document.querySelector('.settings-sidebar').classList.toggle('r-0')
    document.querySelector('.black-overlay').classList.toggle('d-block')
}

// Find submit types and toggle them loading
window.toggleBtnLoading = () => {
    document.querySelector('[type=submit]').toggleAttribute('disabled')
    document.querySelector('[type=submit] .btn-enabled').classList.toggle('d-none')
    document.querySelector('[type=submit] .btn-disabled').classList.toggle('d-none')
}

/**
 * Serialize nested sortable
 *
 * @param sortable
 * @param sortableGroup
 * @returns {[]}
 */
window.nestedSortableSerialize = (sortable, sortableGroup) => {
    const serialized = [];
    const children = [].slice.call(sortable.children);
    for (let i in children) {
        const nested = children[i].querySelector(sortableGroup)

        // Find the closest sortable group and get it's data-parent-id attribute
        const parentId = children[i].closest(sortableGroup).getAttribute('data-parent-id')
        serialized.push({
            item_id: children[i].dataset['sortableId'],
            parent_id: parentId
        });
        if (nested){
            serialized.push(...nestedSortableSerialize(nested, sortableGroup))
        }
    }
    return serialized
}

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
})

window.bottomAlert = ({ text, timeout = null }) => {
    const quickAlert = document.querySelector('.quick-alert')
    quickAlert.classList.add('show')
    quickAlert.querySelector('.text p').textContent = text
    if (timeout){
        setTimeout(() => {
            quickAlert.classList.remove('show')
        }, timeout)
    }
}

document.querySelectorAll('.has-dd').forEach(dd => {
    dd.addEventListener('click', () => {
        dd.querySelector('.menu-dd').classList.toggle('show')
    })
})

// TODO: jquery to pure js
window.openModal = selector => $(selector).modal('show')
window.closeModal = selector => $(selector).modal('hide')
window.changeModalTitle = (selector, title) => $(`${selector} .modal-title`).text(title)

/* /Global functions */


