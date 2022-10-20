var addBtn = jQuery('.coupon-form-nav-wrapper .coupon-form-nav-element.add');
var removeBtn = jQuery('.coupon-form-nav-wrapper .coupon-form-nav-element.remove');
var printBtn = jQuery('.coupon-form-nav-wrapper .coupon-form-nav-element.print');
var addCardBtn = jQuery('.cards-form-nav-wrapper .cards-form-nav-element');

function addCouponToggle()
{
    if(removeBtn.hasClass('active'))
    {
        jQuery('.coupon-remove-form-wrapper').slideToggle( 'slow');
        removeBtn.removeClass('active');
    }
    if(printBtn.hasClass('active'))
    {
        jQuery('.coupon-print_code-form-wrapper').slideToggle( 'slow' );
        printBtn.removeClass('active');
    }
    if(addCardBtn.hasClass('active'))
    {
        jQuery('.card-add_code-form-wrapper').slideToggle( 'slow' );
        addCardBtn.removeClass('active');
    }
    jQuery('.coupon-add-form-wrapper').slideToggle( 'slow' );
    addBtn.toggleClass('active');
}

function removeCouponToggle()
{
    if(addBtn.hasClass('active'))
    {
        jQuery('.coupon-add-form-wrapper').slideToggle( 'slow');
        addBtn.removeClass('active');
    }
    if(printBtn.hasClass('active'))
    {
        jQuery('.coupon-print_code-form-wrapper').slideToggle( 'slow' );
        printBtn.removeClass('active');
    }
    if(addCardBtn.hasClass('active'))
    {
        jQuery('.card-add_code-form-wrapper').slideToggle( 'slow' );
        addCardBtn.removeClass('active');
    }
    jQuery('.coupon-remove-form-wrapper').slideToggle( 'slow' );
    removeBtn.toggleClass('active');
}

function print_codeToggle()
{
    if(addBtn.hasClass('active'))
    {
        jQuery('.coupon-add-form-wrapper').slideToggle( 'slow');
        addBtn.removeClass('active');
    }
    if(removeBtn.hasClass('active'))
    {
        jQuery('.coupon-remove-form-wrapper').slideToggle( 'slow' );
        removeBtn.removeClass('active');
    }
    if(addCardBtn.hasClass('active'))
    {
        jQuery('.card-add_code-form-wrapper').slideToggle( 'slow' );
        addCardBtn.removeClass('active');
    }
    jQuery('.coupon-print_code-form-wrapper').slideToggle( 'slow' );
    printBtn.toggleClass('active');
}

function add_cardToggle()
{
    if(addBtn.hasClass('active'))
    {
        jQuery('.coupon-add-form-wrapper').slideToggle( 'slow');
        addBtn.removeClass('active');
    }
    if(removeBtn.hasClass('active'))
    {
        jQuery('.coupon-remove-form-wrapper').slideToggle( 'slow' );
        removeBtn.removeClass('active');
    }
    if(printBtn.hasClass('active'))
    {
        jQuery('.coupon-print_code-form-wrapper').slideToggle( 'slow' );
        printBtn.removeClass('active');
    }
    jQuery('.card-add_code-form-wrapper').slideToggle( 'slow' );
    addCardBtn.toggleClass('active');
}

jQuery('#loyalityCard-add #card-add-input-cardNum').keypress(
function(event){
    if (event.which == '13') {
    event.preventDefault();
    }
});

jQuery('#card-add-input-mail').focusout(function(){
    var email = jQuery(this).val();
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var isEmail = regex.test(email);
    if(!isEmail)
    {
        alert("Email nie jest poprawny! \r\nPowinien mieć formę test@mail.com");
    }
});

jQuery('#card-add-input-tel').focusout(function(){
    var tel = jQuery(this).val();
    var regex = /^[0-9]{9}$/;
    var isTel = regex.test(tel);
    if(!isTel)
    {
        alert("Numer telefonu nie jest poprawny! \r\nPowinien mieć format 730602626");
    }
});

addBtn.click(addCouponToggle);
removeBtn.click(removeCouponToggle);
printBtn.click(print_codeToggle);
addCardBtn.click(add_cardToggle);