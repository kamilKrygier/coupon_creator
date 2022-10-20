<?php

function couponForm()
{
    wp_enqueue_style( 'style-coupon-creator', plugin_dir_url( __FILE__ )."assets/css/style.css" );
    ?>
    <div class="six_nav_wrapper">
        <div class="coupon-form-nav-wrapper">
            <p style="position: absolute;top: -18px;left: 50%;transform:translateX(-50%);font-weight: 900;font-size: 18px;color: #262626;">Bony podarunkowe</p>
            <a class="coupon-form-nav-element add">
                <img src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/images/plus-solid-icon.svg" alt="Dodaj bon">
                <p style="text-align:center;">Dodaj bon</p>
            </a>
            <a class="coupon-form-nav-element remove">
                <img src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/images/trash-solid-icon.svg" alt="Wykorzystaj bon">
                <p style="text-align:center;">Wykorzystaj bon</p>
            </a>
            <a class="coupon-form-nav-element print">
                <img src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/images/print-solid-icon.svg" alt="Drukuj kod">
                <p style="text-align:center;">Drukuj kod bonu</p>
            </a>
        </div>
        <div class="six_nav_separator"></div>
        <div class="cards-form-nav-wrapper">
            <p style="position: absolute;top: -18px;left: 50%;transform:translateX(-50%);font-weight: 900;font-size: 18px;color: #262626;width: max-content;">Karty stałego klienta</p>
            <a class="cards-form-nav-element add-card">
                <img src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/images/credit-card-solid-icon.svg" alt="Dodaj kartę">
                <p style="text-align:center;">Dodaj kartę</p>
            </a>
        </div>
    </div>

    <div class="coupon-add-form-wrapper">
        <form id="coupon-add">
            <h1 style="text-align: center;">Dodaj bon</h1>
            <hr>
            <label for="coupon-value-input">Wartość bonu:<br>
                <input id="coupon-value-input" type="number" min="50" placeholder="500" required>
            </label>
            <label for="coupon-date-input">Kiedy bon ma się przeterminować:<br>
                <input id="coupon-date-input" type="date" required>
            </label>
            <input class="x-btn" type="submit" value="Utwórz bon">
        </form>
    </div>

    <div class="coupon-remove-form-wrapper">
        <form id="coupon-remove">
        <h1 style="text-align: center;">Wykorzystaj bon</h1>
        <hr>
            <label for="coupon-code-remove-input"> Podaj kod bonu:<br>
                <input id="coupon-code-remove-input" type="text" placeholder="GK493ATx#" required>
            </label>
            <input class="x-btn" type="submit" value="Wykorzystaj bon">
        </form>
    </div>
    
    <div class="coupon-print_code-form-wrapper">
        <form id="coupon-check">
        <h1 style="text-align: center;">Drukuj kod</h1>
        <hr>
            <label for="coupon-code-print-code-input"> Podaj kod bonu:<br>
                <input id="coupon-code-print-code-input" type="text" placeholder="GK493ATx#" required>
            </label>
            <input class="x-btn" type="submit" value="Drukuj kod">
        </form>
    </div>

    <div class="card-add_code-form-wrapper">
        <form id="loyalityCard-add">
        <h1 style="text-align: center;">Dodaj klienta do karty</h1>
        <hr>
            <label for="card-add-input-cardNum"> Podaj kod z karty:<br>
                <input id="card-add-input-cardNum" type="number" min="70064030000001" max="70064030099999" placeholder="70064030000001" required>
            </label>
            <label for="card-add-input-name"> Imię<br>
                <input id="card-add-input-name" type="text" placeholder="Jan" required>
            </label>
            <label for="card-add-input-surname"> Nazwisko:<br>
                <input id="card-add-input-surname" type="text" placeholder="Kowalski" required>
            </label>
            <label for="card-add-input-mail"> Adres e-mail:<br>
                <input id="card-add-input-mail" type="email" placeholder="adres@mail.pl" required>
            </label>
            <label for="card-add-input-tel"> Numer telefonu:<br>
                <input id="card-add-input-tel" type="tel" placeholder="730602626" required>
            </label>
            <label for="card-add-input-date"> Data urodzin:<br>
                <input id="card-add-input-date" type="date" placeholder="dd.mm.rrr" required>
            </label>
            <input class="x-btn" type="submit" value="Zapisz kartę">
        </form>
    </div>

    <div class="six_coupon_message_box">

    </div>
    <div class="six_loader_wrapper">
        <div class="six_loader">
        
        </div>
    </div>
    <?php
}

add_shortcode('coupon-creator', 'couponForm'); 
?>