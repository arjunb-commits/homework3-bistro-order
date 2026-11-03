<?php
// Nothing may be printed above this line: order_functions.php calls session_start().
require_once __DIR__ . '/order_functions.php';

// Cookie = recognition only. The order itself lives in the session.
$lastVisit = $_COOKIE['lastVisit'] ?? null;
$welcomeBack = '';
if ($lastVisit !== null && ctype_digit((string) $lastVisit)) {
   $welcomeBack = 'Welcome back — your last visit was '
      . date('l, F j, Y \a\t g:i a', (int) $lastVisit) . '.';
}

// Saved values for the live receipt on the right-hand side.
$dessert = sessionValue('dessert');
$drink = sessionValue('drink');
$drinkSize = sessionValue('drinkSize');
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Chez Session &ndash; Build Your Order</title>
   <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="page">

   <header class="site-header">
      <h1>Chez Session</h1>
      <nav aria-label="Main">
         <a href="index.php">The dining room</a>
         <a href="about.php">How it works</a>
      </nav>
   </header>

   <?php if ($welcomeBack !== ''): ?>
   <p class="welcome-note"><?= htmlspecialchars($welcomeBack); ?></p>
   <?php endif; ?>

   <main class="layout">

      <section class="order-column">
         <form action="process_order.php" method="post">

            <label class="field-label" for="dessert"><span aria-hidden="true">🍰</span> Dessert</label>
            <select id="dessert" name="dessert">
               <option value="none" <?= dessertSelected('none'); ?>>Choose a dessert</option>
               <option value="Cheesecake" <?= dessertSelected('Cheesecake'); ?>>Cheesecake</option>
               <option value="Chocolate Cake" <?= dessertSelected('Chocolate Cake'); ?>>Chocolate Cake</option>
               <option value="Carrot Cake" <?= dessertSelected('Carrot Cake'); ?>>Carrot Cake</option>
               <option value="Tiramisu" <?= dessertSelected('Tiramisu'); ?>>Tiramisu</option>
            </select>

            <label class="field-label" for="drink"><span aria-hidden="true">☕</span> Drink</label>
            <select id="drink" name="drink">
               <option value="none" <?= drinkSelected('none'); ?>>Choose a drink</option>
               <option value="Coffee" <?= drinkSelected('Coffee'); ?>>Coffee</option>
               <option value="Hot Chocolate" <?= drinkSelected('Hot Chocolate'); ?>>Hot Chocolate</option>
               <option value="Root Beer" <?= drinkSelected('Root Beer'); ?>>Root Beer</option>
               <option value="Tea" <?= drinkSelected('Tea'); ?>>Tea</option>
               <option value="Milk" <?= drinkSelected('Milk'); ?>>Milk</option>
            </select>

            <fieldset class="size-group">
               <legend><span aria-hidden="true">🥤</span> Drink size</legend>
               <div class="size-options">
                  <input type="radio" id="size-small" name="drinkSize" value="Small" <?= drinkSizeSelected('Small'); ?>>
                  <label for="size-small">Small</label>

                  <input type="radio" id="size-medium" name="drinkSize" value="Medium" <?= drinkSizeSelected('Medium'); ?>>
                  <label for="size-medium">Medium</label>

                  <input type="radio" id="size-large" name="drinkSize" value="Large" <?= drinkSizeSelected('Large'); ?>>
                  <label for="size-large">Large</label>
               </div>
            </fieldset>

            <div class="button-row">
               <button type="submit" class="btn btn-primary">Send to Kitchen <span aria-hidden="true">🧑‍🍳</span></button>
               <a class="btn btn-outline" href="index.php">Order Again <span aria-hidden="true">🔁</span></a>
               <a class="btn btn-forget" href="forget_order.php">Forget Order <span aria-hidden="true">🗑️</span></a>
            </div>
         </form>

         <div class="insight-card">
            <h3>Live guidance</h3>
            <p><?= htmlspecialchars(completionMessage()); ?></p>
            <p class="insight-note"><?= htmlspecialchars(pairingSuggestion()); ?></p>
         </div>
      </section>

      <aside class="receipt-column">
         <section class="receipt-card" aria-label="Current order">
            <h2 class="receipt-title">Chez Session &middot; Receipt</h2>
            <div class="receipt-rule"></div>
            <dl class="receipt-rows">
               <div><dt>Dessert</dt><dd><?= htmlspecialchars($dessert); ?></dd></div>
               <div><dt>Drink</dt><dd><?= htmlspecialchars($drink); ?></dd></div>
               <div><dt>Drink size</dt><dd><?= htmlspecialchars($drinkSize); ?></dd></div>
            </dl>
            <div class="receipt-rule receipt-tear"></div>
            <dl class="receipt-rows">
               <div class="receipt-total"><dt>Estimated total</dt><dd><?= htmlspecialchars(formattedTotal()); ?></dd></div>
               <div><dt>Order profile</dt><dd><?= htmlspecialchars(orderProfile()); ?></dd></div>
            </dl>
            <p class="insight-note"><?= htmlspecialchars(pricingNote()); ?></p>
         </section>

         <?= sessionInspector(); ?>
      </aside>

   </main>
</div>
</body>
</html>
