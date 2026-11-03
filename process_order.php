<?php
// Mission 5: stamp the visit BEFORE any output (including stray whitespace).
setcookie('lastVisit', (string) time(), time() + 86400, '/');

// order_functions.php calls session_start() — still before any output.
require __DIR__ . '/order_functions.php';

// Read the POST, store the three session variables, get the receipt summary.
$orderSummary = order();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Chez Session &ndash; Order Received</title>
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

   <main class="layout">

      <section class="order-column">
         <h2 class="page-heading">Order sent to the kitchen</h2>

         <div class="insight-card">
            <h3>Order feedback</h3>
            <p><?= htmlspecialchars(completionMessage()); ?></p>
            <p class="insight-note"><?= htmlspecialchars(pairingSuggestion()); ?></p>
         </div>

         <div class="button-row">
            <a class="btn btn-primary" href="index.php">Order Again! <span aria-hidden="true">🔁</span></a>
            <a class="btn btn-forget" href="forget_order.php">Forget Order <span aria-hidden="true">🗑️</span></a>
         </div>
      </section>

      <aside class="receipt-column">
         <section class="receipt-card" aria-label="Receipt">
            <h2 class="receipt-title">Chez Session &middot; Receipt</h2>
            <div class="receipt-rule"></div>
            <div class="receipt-summary"><?= $orderSummary; ?></div>
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
