export default function layoutView(){
    const layoutButton = document.querySelectorAll('.layout-view__single');
    const cardsGrid = document.querySelector('.fd-woo__shop-grid');

    layoutButton.forEach(el=>{
        el.addEventListener('click', (e)=>{
            console.log(cardsGrid);
            if(e.target.id === 'grid'){
                if(!cardsGrid.classList.contains('grid-view')){
                    cardsGrid.classList.add('grid-view');
                }
            }else{
                cardsGrid.classList.remove('grid-view');
            }
        })
    })

}