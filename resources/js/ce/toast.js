import '../../sass/ce/toast.scss'
const toastEl = document.querySelector('.ce-toast-container')

const ceToast = ({status, title, message, transition = 'zoom', timeout = 3500, progress = true, addition = ''}) => {
    let icon, toastClass;
    if (status === 1){
        icon = 'check'
        toastClass = 'success';
    }else if(status === 2){
        icon = 'info'
        toastClass = 'info';
    }else if(status === 3){
        icon = 'warning'
        toastClass = 'warning';
    }else if(status === 0){
        icon = 'close'
        toastClass = 'error';
    }else{
        toastClass = 'default'
    }

    const toastId = document.querySelectorAll('.ce-toast').length;

    toastEl.insertAdjacentHTML('afterbegin', `
        <div class="ce-toast ${toastClass} ${transition}" data-id="${toastId}">
            <div class="content">
                <h3>${title} <i class="material-icons-outlined md-18">${icon}</i></h3>
                <p>${message}</p>
            </div>
            <a class="close" onclick="clearToast(${toastId})"><i class="material-icons-outlined md-18">close</i></a>
            <div class="addition">${addition}</div>
            <div class="progress"></div>
        </div>
    `);

    // TODO: do not need to use setTimeout
    setTimeout(() => {
        document.querySelector(`.ce-toast[data-id='${toastId}']`).classList.add('show')
    }, 50)

    if (progress){
        let width = 0;
        const progressInterval = setInterval(() => {
            if (width >= 100){
                clearInterval(progressInterval)
            }else{
                width++
                toastProgress(toastId, width)
            }
        }, timeout / 100)
    }

    setTimeout(() => {
        clearToast(toastId)
    }, timeout)
}

window.clearToast = (toastId) => {
    const toast = document.querySelector(`.ce-toast[data-id='${toastId}']`)
    toast.classList.remove('show')
    setTimeout(() => {
        toast.remove()
    }, 500)
}
//const clearAll = () => toastEl.querySelectorAll('*').forEach(n => n.remove())
const toastProgress = (toastId, width) => {
    const toast = document.querySelector(`.ce-toast[data-id='${toastId}']`)
    if (toast) toast.querySelector('.progress').style.width = width + "%"
}

export default ceToast
