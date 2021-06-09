export default function layoutView(){
    const layoutButton = document.querySelectorAll('.layout-view__single');
    const cardsGrid = document.querySelector('.fd-woo__shop-grid');
    if(sessionStorage.layout === 'grid'){
        cardsGrid.classList.add('grid-view');
    }



    layoutButton.forEach(el=>{
        
        el.addEventListener('click', (e)=>{
            layoutButton.forEach(bt=>{
                bt.classList.remove('active');
            })
            el.classList.add('active')
            if(e.target.id === 'grid' || e.target.closest('#grid')){
                if(!cardsGrid.classList.contains('grid-view')){
                    cardsGrid.classList.add('grid-view');
                    sessionStorage.setItem('layout', 'grid');
                }
            }else{
                cardsGrid.classList.remove('grid-view');
                sessionStorage.setItem('layout', 'tiles');
            }
        })
    })

}