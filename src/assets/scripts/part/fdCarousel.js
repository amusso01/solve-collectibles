import Glide from '@glidejs/glide'

export default function fdCarousel(){
    // Hero
    new Glide('.glide-hero').mount()
    new Glide('.glide-hero-mobile').mount()

    // Collections
    new Glide('.glide-collection', {
        type: 'carousel',
        startAt: 0,
        perView: 5,
        gap: 0,
        autoplay: 4000,
        breakpoints: {
            1320: {
                perView: 4
            },
            1024: {
              perView: 3
            },
            600: {
              perView: 2
            }
          }
    }).mount();

     // Products new Home
    new Glide('.glide-products', {
      type: 'carousel',
      startAt: 0,
      perView: 5,
      gap: 0,

      breakpoints: {
          1320: {
              perView: 4
          },
          1024: {
            perView: 3
          },
          600: {
            perView: 2
          }
        }
  }).mount()
}