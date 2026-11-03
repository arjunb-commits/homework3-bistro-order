<?php
// about.php is a static explainer, so it needs no session of its own.
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Chez Session &ndash; How It Works</title>
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

   <main class="about">
      <h2>How an order travels through the bistro</h2>
      <p>
         Web pages have no memory. Every click is a brand-new request, and the server
         forgets you the moment it answers. This site fixes that with a
         <strong>session</strong>, so your order is still there when you come back to the form.
      </p>

      <ol class="steps">
         <li>
            <h3>1. You open the dining room</h3>
            <p>
               <code>index.php</code> loads <code>order_functions.php</code>, whose first line is
               <code>session_start()</code>. PHP hands your browser a session cookie
               (<code>PHPSESSID</code>) and wakes up <code>$_SESSION</code>.
               If you have ordered before, <code>dessertSelected()</code>,
               <code>drinkSelected()</code> and <code>drinkSizeSelected()</code> compare each option
               with the saved value and print <code>selected</code> or <code>checked</code>.
            </p>
         </li>
         <li>
            <h3>2. You send the order to the kitchen</h3>
            <p>
               The form posts <code>dessert</code>, <code>drink</code> and <code>drinkSize</code>
               to <code>process_order.php</code>. Your browser sends the session cookie along,
               so PHP knows which session is yours.
            </p>
         </li>
         <li>
            <h3>3. The kitchen saves it</h3>
            <p>
               <code>process_order.php</code> stamps a <code>lastVisit</code> cookie, then requires
               <code>order_functions.php</code> and calls <code>order()</code>.
               That function reads <code>$_POST</code> (with <code>?? ''</code> so a missing field
               cannot crash the page), replaces an empty or <code>none</code> value with
               <code>(not selected)</code>, stores all three in <code>$_SESSION</code>, and
               returns the receipt text.
            </p>
         </li>
         <li>
            <h3>4. You order again</h3>
            <p>
               The Order Again! link goes back to <code>index.php</code>. The session is still alive,
               so the helpers re-select your dessert and drink and re-check your size.
            </p>
         </li>
         <li>
            <h3>5. The bistro forgets you</h3>
            <p>
               <code>forget_order.php</code> opens the session (you must start it to clear it),
               unsets the order variables, runs <code>session_destroy()</code>, and redirects to
               <code>index.php</code>, which now shows a clean form.
            </p>
         </li>
      </ol>

      <h2>What a session is, and why it fits</h2>
      <p>
         A session keeps data on the <em>server</em> and gives the browser only a random ID to
         look it up. Your choices never travel in the page or in a cookie, so they cannot be
         edited in the browser, and they disappear when the session is destroyed. An order is
         temporary, belongs to one visitor, and should be easy to reset, which is exactly what
         sessions are built for.
      </p>

      <h2>Sessions and cookies</h2>
      <p>
         The <code>lastVisit</code> cookie is stored in the browser and lasts 24 hours. It only
         lets the form say &ldquo;Welcome back&rdquo;. It never holds the order itself, because
         cookies can be read and changed by the visitor.
      </p>

      <h2>Two classic mistakes</h2>
      <p class="pitfall">
         <strong>Output before <code>session_start()</code>.</strong>
         A blank line, a space or any HTML before it means PHP can no longer send the session
         cookie, and you get a &ldquo;headers already sent&rdquo; warning. The same rule applies to
         <code>setcookie()</code> and <code>header()</code>.
      </p>
      <p class="pitfall">
         <strong><code>selected</code> versus <code>checked</code>.</strong>
         Dropdown options use <code>selected</code>. Radio buttons use <code>checked</code>.
         Using the wrong one means the saved choice is not restored.
      </p>
   </main>
</div>
</body>
</html>
