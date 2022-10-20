jQuery('#coupon-add').submit(function( event ) 
{
    event.preventDefault();
    
    var coupon_value = jQuery('#coupon-value-input').val();
    var coupon_expiration = jQuery('#coupon-date-input').val();
    
    if(confirm(`Wartość kuponu: ${coupon_value} \r\nData ważności kuponu: ${coupon_expiration} \r\nCzy jesteś pewna/ny, że chcesz utworzyć taki kupon?`))
    {
        jQuery('.six_loader_wrapper').fadeIn('slow');
        jQuery.ajax({
            type: 'POST',
            // dataType: 'html',
            data: {
                action: "create_coupon_code",
                coupon_value: ajax.coupon_value = coupon_value,
                coupon_expiration: ajax.coupon_expiration = coupon_expiration,
                nonce: ajax.nonce,
            },
            url: ajax.url,
            success: function (data) {
                jQuery('.six_coupon_message_box').addClass('active');
                if( data[data.length-1] == 0 ){
                    jQuery('.six_coupon_message_box').append(data.slice(0, -1)); 
                }
                else jQuery('.six_coupon_message_box').append(data);  
            },
            error: function()
            {
                jQuery(".six_loader_wrapper").fadeOut("slow");
                alert("Błąd połączenia");
            },
        });  
    }
});

jQuery('#coupon-remove').submit(function( event ) 
{
    event.preventDefault();

    var coupon_code = jQuery('#coupon-code-remove-input').val();

    if(confirm(`Czy na pewno chcesz wykorzystać kupon: ${coupon_code} ?`))
    {
        jQuery('.six_loader_wrapper').fadeIn('slow');
        jQuery.ajax({
            type: 'POST',
            // dataType: 'html',
            data: {
                action: "remove_coupon_code",
                coupon_code: ajax.coupon_code = coupon_code,
                nonce: ajax.nonce,
            },
            url: ajax.url,
            success: function (data) {
                jQuery('.six_coupon_message_box').addClass('active'); 
                if( data[data.length-1] == 0 ){
                    jQuery('.six_coupon_message_box').append(data.slice(0, -1)); 
                }
                else jQuery('.six_coupon_message_box').append(data); 
            },
            error: function()
            {
                jQuery(".six_loader_wrapper").fadeOut("slow");
                alert("Błąd połączenia");
            },
        });
    }
});

jQuery('#coupon-check').submit(function( event ) 
{
    event.preventDefault();

    var coupon_code = jQuery('#coupon-code-print-code-input').val();

    if(confirm(`Czy chcesz wydrukować kupon: ${coupon_code} ?`))
    { 
        jQuery('.six_loader_wrapper').fadeIn('slow');
        jQuery.ajax({
            type: 'POST',
            // dataType: 'html',
            data: {
                action: "print_code",
                coupon_code: ajax.coupon_code = coupon_code,
                nonce: ajax.nonce,
            },
            url: ajax.url,
            success: function (data) {
                jQuery('.six_coupon_message_box').addClass('active'); 
                if( data[data.length-1] == 0 ){
                    jQuery('.six_coupon_message_box').append(data.slice(0, -1)); 
                }
                else jQuery('.six_coupon_message_box').append(data); 
            },
            error: function()
            {
                jQuery(".six_loader_wrapper").fadeOut("slow");
                alert("Błąd połączenia");
            },
    });  
    }
    
});

jQuery('#loyalityCard-add').submit(function( event ) 
{
    event.preventDefault();

    var card_code = jQuery('#card-add-input-cardNum').val();
    var card_name = jQuery('#card-add-input-name').val();
    var card_surname = jQuery('#card-add-input-surname').val();
    var card_mail = jQuery('#card-add-input-mail').val();
    var card_tel = jQuery('#card-add-input-tel').val();
    var card_date = jQuery('#card-add-input-date').val();

    if(confirm(`Czy jesteś pewna/y, że chcesz przypisać kartę: ${card_code} ? \r\nDane do karty: \r\n Imię: ${card_name} \r\n Nazwisko: ${card_surname} \r\n Adres email: ${card_mail} \r\n Numer tel: ${card_tel} \r\n Data urodzin: ${card_date}`))
    {
        jQuery('.six_loader_wrapper').fadeIn('slow');
        jQuery.ajax({
            type: 'POST',
            // dataType: 'html',
            data: {
                action: "add_card",
                card_code: ajax.card_code = card_code,
                card_name: ajax.card_name = card_name,
                card_surname: ajax.card_surname = card_surname,
                card_mail: ajax.card_mail = card_mail,
                card_tel: ajax.card_tel = card_tel,
                card_date: ajax.card_date = card_date,
                nonce: ajax.nonce,
            },
            url: ajax.url,
            success: function (data) {
                jQuery('.six_coupon_message_box').addClass('active');
                if( data[data.length-1] == 0 ){
                    jQuery('.six_coupon_message_box').append(data.slice(0, -1)); 
                }
                else jQuery('.six_coupon_message_box').append(data); 
            },
            error: function()
            {
                jQuery(".six_loader_wrapper").fadeOut("slow");
                alert("Błąd połączenia");
            },
        });
    }
    
});