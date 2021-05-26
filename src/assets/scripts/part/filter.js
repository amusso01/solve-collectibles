export default function fdFilter(){
    const button = document.getElementById('fdFilterButton')
    const filter = document.getElementById('fdFilter')
    const filterClose = document.querySelector('.filter-close')

    button.addEventListener('click', (e)=>{
        filter.classList.toggle('filter-open')
    })

    filterClose.addEventListener('click', ()=>{
        filter.classList.remove('filter-open')
    })

}