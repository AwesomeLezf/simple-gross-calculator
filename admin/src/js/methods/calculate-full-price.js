
const price = document.querySelector('.js-sgc-price');
const vat = document.querySelector('.js-sgc-vat');
const priceFull = document.querySelector('.js-sgc-price-full');

price.addEventListener('change', ()=> {
    calculateFullPrice();
})
vat.addEventListener('change', ()=> {
    calculateFullPrice();
})

function calculateFullPrice(){
    if( price.value !== '')
        priceFull.value = parseFloat(price.value) + ( parseFloat(price.value) * parseFloat(vat.value));
    else
        priceFull.value = '';
}

