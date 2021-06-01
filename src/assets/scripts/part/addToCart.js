export default function addToCart(){
  
    const ajaxButton = document.querySelectorAll('.ajax_add_to_cart');

    ajaxButton.forEach(element => {
        element.addEventListener('click', animaAdd);
    });

     function animaAdd(e){
         let thisButton = e.target
        thisButton.classList.add('disabled');

        setTimeout(() => {
            let viewCart = thisButton.nextSibling;
            thisButton.classList.remove('disabled');
        }, 3000);
     }

}