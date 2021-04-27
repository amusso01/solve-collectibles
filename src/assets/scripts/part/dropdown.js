export default function dropdown(){
    let showDelay = 100, hideDelay = 200;
    let menuEnterTimer, menuLeaveTimer;

    const parent = document.querySelectorAll('.menu-item-has-children');

    parent.forEach((link)=>{
  
        link.addEventListener('mouseenter', (el)=>{
            clearTimeout(menuLeaveTimer);
            let submenu = link.querySelector('.sub-menu');
            for (let j = 0; j < parent.length; j++) {
                let innerEl = parent[j].querySelector('.sub-menu');
                if(parent[j]!==link){
                    innerEl.classList.remove('active')
                }
			}
            menuEnterTimer = setTimeout(function() {
				submenu.classList.add('active');
			}, showDelay);
          
         
        })

        link.addEventListener('mouseleave', (el)=>{
            let submenu = link.querySelector('.sub-menu');
            clearTimeout(menuEnterTimer);
            menuLeaveTimer = setTimeout(function() {
                submenu.classList.remove('active')
            }, hideDelay);
      
        })
       
    })

}

