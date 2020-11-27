// TODO: check all window object definitions imported including
import './bootstrap'
import 'datatables.net'
import './setups'

import { STORAGE_PREFIX } from "./config";

/* Global functions */
const setHtmlTheme = theme => document.querySelector('html').setAttribute('theme', theme);

window.addEventListener('DOMContentLoaded', () => {
    // If user is in tablet just close menu at start
    const mediaQuery = window.matchMedia('(max-width: 767px)')
    if (mediaQuery.matches) {
        document.getElementById('menu-toggle').removeAttribute('checked')
    }
})

window.addEventListener('load', () => {
    // Remove loader
    document.getElementById('loader').remove()

    const theme = localStorage.getItem(`${STORAGE_PREFIX}_theme`) || 'light-theme'
    document.getElementById(theme).setAttribute('checked', true)
    setHtmlTheme(theme)
})

// Set theme
window.toggleTheme = (theme) => {
    localStorage.setItem(`${STORAGE_PREFIX}_theme`, theme)
    setHtmlTheme(theme)
}

// Open the theme settings sidebar
window.toggleThemeSettings = () => {
    document.querySelector('.settings-sidebar').classList.toggle('r-0')
    document.querySelector('.black-overlay').classList.toggle('d-block')
}

/*
 Find submit types and toggle them loading
 */
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

// TODO: jquery to pure js
window.openModal = selector => $(selector).modal('show')
window.closeModal = selector => $(selector).modal('hide')
window.changeModalTitle = (selector, title) => $(`${selector} .modal-title`).text(title)

/* /Global functions */


