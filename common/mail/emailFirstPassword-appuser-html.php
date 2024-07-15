<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */


?>
<div class="verify-email">
    <p>Hallo,</p> 

Welkom bij de businessclub app van VVOG! Je bent er bijna.<br><br>

Klik op onderstaande link om naar de inlogpagina van de app te gaan. De app is een webapp, waardoor je deze niet hoeft te downloaden vanuit diverse appstores, maar is via je browser op je telefoon te openen.<br><br>

<?=Html::a('https://vvog.businessonly.nl','https://vvog.businessonly.nl');?><br><br>

Afhankelijk van je telefoon of browser, zal de app direct worden geïnstalleerd op je beginscherm of moet je hem zelf toevoegen door te klikken op de button "Toevoegen aan beginscherm"
<br><br>
 Je Gebruikersnaam is je emailadres.
<br><br>
Het wachtwoord is: <?=$user->password?>
<br><br>
 Na het inloggen wordt gevraagd door de browser om het wachtwoord te bewaren. Dit werkt volgens de beveiligingsinstellingen zoals die voor jou persoonlijk zijn ingesteld op je telefoon.
<br><br> 
Wij wensen je veel plezier en gaan er vanuit dat de businessclub app zal bijdragen aan het versterken van onderlinge contacten met alle leden van de Businessclub van VVOG.
<br><br>
Deze businessclub app is een product van businessonly.nl. Voor vragen kun je contact opnemen via sales@businessonly.nl of kijk op www.businessonly.nl.
<br><br>
Met vriendelijke groeten,<br>
Team Onboarding
<br><br>
Businessonly    
</div>
