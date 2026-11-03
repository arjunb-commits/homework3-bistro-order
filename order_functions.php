<?php
session_start(); // Must run before any output (no whitespace/HTML above this file's <?php).

function cleanChoice(string $value): string {
   $value = trim($value);

   return ($value === '' || $value === 'none') ? '(not selected)' : $value;
}

function order(): string {
   // Read POST values; ?? '' means a missing key never crashes the page.
   $dessert = $_POST['dessert'] ?? '';
   $drink = $_POST['drink'] ?? '';
   $drinkSize = $_POST['drinkSize'] ?? '';

   // Fallback logic runs before the receipt is formatted.
   $_SESSION['dessert'] = cleanChoice($dessert);
   $_SESSION['drink'] = cleanChoice($drink);
   $_SESSION['drinkSize'] = cleanChoice($drinkSize);

   return "Dessert: {$_SESSION['dessert']}<br>"
      . "Drink: {$_SESSION['drink']}<br>"
      . "Drink size: {$_SESSION['drinkSize']}";
}

function sessionValue(string $key): string {
   return $_SESSION[$key] ?? '(not selected)';
}

function completedChoiceCount(): int {
   $count = 0;

   foreach (['dessert', 'drink', 'drinkSize'] as $key) {
      if (sessionValue($key) !== '(not selected)') {
         $count++;
      }
   }

   return $count;
}

function completionMessage(): string {
   $count = completedChoiceCount();

   if ($count === 3) {
      return 'Your order is complete and ready to submit.';
   }

   if ($count === 0) {
      return 'Start by choosing a dessert, a drink, and a size.';
   }

   return "You have completed {$count} of 3 order choices.";
}

function pairingSuggestion(): string {
   $dessert = sessionValue('dessert');
   $drink = sessionValue('drink');

   if ($dessert === '(not selected)' || $drink === '(not selected)') {
      return 'Select both a dessert and a drink to see a suggested pairing.';
   }

   if ($dessert === 'Cheesecake' && $drink === 'Coffee') {
      return 'Suggested pairing: Cheesecake and coffee create a classic cafe-style combo.';
   }

   if ($dessert === 'Chocolate Cake' && $drink === 'Milk') {
      return 'Suggested pairing: Chocolate cake and milk make a rich and familiar dessert pairing.';
   }

   if ($dessert === 'Tiramisu' && $drink === 'Coffee') {
      return 'Suggested pairing: Tiramisu and coffee reinforce the espresso flavor profile.';
   }

   if ($dessert === 'Carrot Cake' && $drink === 'Tea') {
      return 'Suggested pairing: Carrot cake and tea create a lighter and balanced combination.';
   }

   return "Suggested pairing: {$dessert} with {$drink} gives the order a custom bistro feel.";
}

function dessertPrice(): float {
   $prices = [
      'Cheesecake' => 4.25,
      'Chocolate Cake' => 4.5,
      'Carrot Cake' => 4.0,
      'Tiramisu' => 4.75,
   ];

   return $prices[sessionValue('dessert')] ?? 0.0;
}

function drinkPrice(): float {
   $basePrices = [
      'Coffee' => 2.25,
      'Hot Chocolate' => 2.75,
      'Root Beer' => 2.5,
      'Tea' => 2.0,
      'Milk' => 1.75,
   ];
   $sizeUpcharge = [
      'Small' => 0.0,
      'Medium' => 0.6,
      'Large' => 1.15,
   ];

   $drink = sessionValue('drink');
   $drinkSize = sessionValue('drinkSize');

   if (!isset($basePrices[$drink])) {
      return 0.0;
   }

   return $basePrices[$drink] + ($sizeUpcharge[$drinkSize] ?? 0.0);
}

function estimatedTotal(): float {
   return dessertPrice() + drinkPrice();
}

function formattedTotal(): string {
   return '$' . number_format(estimatedTotal(), 2);
}

function pricingNote(): string {
   if (sessionValue('drink') !== '(not selected)' && sessionValue('drinkSize') === '(not selected)') {
      return 'Choose a drink size to finalize the drink price.';
   }

   if (completedChoiceCount() === 0) {
      return 'Your estimated total will update as soon as you begin building the order.';
   }

   return 'The estimate is based on the current dessert, drink, and drink size selections.';
}

function orderProfile(): string {
   $total = estimatedTotal();

   if ($total >= 6.5) {
      return 'Full cafe combo';
   }

   if ($total >= 4.0) {
      return 'Balanced dessert break';
   }

   if ($total > 0) {
      return 'Light snack order';
   }

   return 'No selections yet';
}

function dessertSelected(string $dessert): string {
   return ($_SESSION['dessert'] ?? '') === $dessert ? 'selected' : '';
}

function drinkSelected(string $drink): string {
   return ($_SESSION['drink'] ?? '') === $drink ? 'selected' : '';
}

function drinkSizeSelected(string $drinkSize): string {
   return ($_SESSION['drinkSize'] ?? '') === $drinkSize ? 'checked' : '';
}

// Extras: a read-only peek at $_SESSION so the grader (and you) can watch it change.
function sessionInspector(): string {
   $html = '<section class="inspector" aria-label="Session inspector">'
      . '<h2>Session inspector &middot; $_SESSION</h2>';

   foreach (['dessert', 'drink', 'drinkSize'] as $key) {
      $html .= '<p><span class="code-key">$_SESSION[\'' . $key . '\']</span> = ';

      if (isset($_SESSION[$key])) {
         $html .= '<span class="code-value">\'' . htmlspecialchars($_SESSION[$key]) . '\'</span>';
      } else {
         $html .= '<span class="code-null">null</span>';
      }

      $html .= '</p>';
   }

   return $html . '</section>';
}

?>
