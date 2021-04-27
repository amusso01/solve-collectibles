export default function mobileMenu(){
    const parentLink = document.querySelectorAll('.menu-item-has-children');
    const back = document.getElementById('backParent')
    parentLink.forEach((link)=>{
        link.addEventListener('touchstart' , e=> {
            e.preventDefault();
            let submenu = link.querySelector('.sub-menu');
            submenu.classList.add('mobileActive');
            back.classList.add('mobileActive');
        })
    })
    back.addEventListener('touchstart', ()=>{
        back.classList.remove('mobileActive');
        for (let j = 0; j < parentLink.length; j++) {
            let innerEl = parentLink[j].querySelector('.sub-menu');
            innerEl.classList.remove('mobileActive')
        }
    })
}