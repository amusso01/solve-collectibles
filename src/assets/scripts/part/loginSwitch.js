export default function switchLogin(){
    const loginSwitch = document.getElementById('loginSwitch')
    const registerSwitch = document.getElementById('registerSwitch')
    const loginTab = document.querySelector('.login-single')
    const registerTab = document.querySelector('.register-single')

    loginSwitch.addEventListener('click' , (e)=>{
        e.preventDefault();
        if(!loginSwitch.classList.contains('active')){
            loginSwitch.classList.add('active')
            registerSwitch.classList.remove('active')
        }
        if(!loginTab.classList.contains('active')){
            loginTab.classList.add('active')
            registerTab.classList.remove('active')
        }
    })

    registerSwitch.addEventListener('click' , (e)=>{
        e.preventDefault();
        if(!registerSwitch.classList.contains('active')){
            registerSwitch.classList.add('active')
            loginSwitch.classList.remove('active')
        }
        if(!registerTab.classList.contains('active')){
            registerTab.classList.add('active')
            loginTab.classList.remove('active')
        }
    })
}