export default function addToCart(){
  
    const ajaxButton = document.querySelectorAll('.ajax_add_to_cart');

    ajaxButton.forEach(element => {
        element.addEventListener('click', animaAdd);
    });

     function animaAdd(e){
         let thisButton = e.target
        thisButton.classList.add('FdDisabled');

        let spinner = setTimeout(() => {
            thisButton.classList.remove('FdDisabled');
            thisButton.classList.add('add');
        }, 2500);

        setTimeout(() => {
            clearTimeout(spinner);
            thisButton.classList.remove('add')
        }, 3800);
     }

}