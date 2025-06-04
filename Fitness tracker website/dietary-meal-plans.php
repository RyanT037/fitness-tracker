<?php
session_start();
include 'includes/db_connect.php';

$mealPlans = [
    [
        'title' => 'Weight Loss Meal Plan',
        'description' => 'A calorie deficit plan focused on lean proteins, vegetables, and whole grains.',
        'calories' => 1500,
        'protein' => '110g',
        'carbs' => '150g',
        'fat' => '40g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Oatmeal with berries and egg whites'],
            ['name' => 'Snack', 'items' => 'Greek yogurt and almonds'],
            ['name' => 'Lunch', 'items' => 'Grilled chicken breast, brown rice, steamed broccoli'],
            ['name' => 'Snack', 'items' => 'Apple and peanut butter'],
            ['name' => 'Dinner', 'items' => 'Baked salmon, quinoa, mixed salad'],
        ]
    ],
    [
        'title' => 'Bodybuilding Meal Plan',
        'description' => 'A high-protein, moderate-carb plan for muscle gain.',
        'calories' => 2800,
        'protein' => '200g',
        'carbs' => '320g',
        'fat' => '80g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Egg omelette, whole grain toast, banana'],
            ['name' => 'Snack', 'items' => 'Protein shake and mixed nuts'],
            ['name' => 'Lunch', 'items' => 'Lean beef, sweet potato, green beans'],
            ['name' => 'Snack', 'items' => 'Cottage cheese and pineapple'],
            ['name' => 'Dinner', 'items' => 'Grilled chicken, brown rice, asparagus'],
        ]
    ],
    [
        'title' => 'Stay Fit (Maintenance) Meal Plan',
        'description' => 'A balanced plan for maintaining weight and overall health.',
        'calories' => 2000,
        'protein' => '130g',
        'carbs' => '220g',
        'fat' => '60g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Greek yogurt parfait with granola'],
            ['name' => 'Snack', 'items' => 'Mixed fruit'],
            ['name' => 'Lunch', 'items' => 'Turkey sandwich, carrot sticks'],
            ['name' => 'Snack', 'items' => 'Rice cakes with hummus'],
            ['name' => 'Dinner', 'items' => 'Grilled fish, roasted potatoes, spinach salad'],
        ]
    ],
    [
        'title' => 'Vegetarian Fitness Meal Plan',
        'description' => 'A plant-based plan for active individuals.',
        'calories' => 1800,
        'protein' => '90g',
        'carbs' => '210g',
        'fat' => '50g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Chia pudding with almond milk and berries'],
            ['name' => 'Snack', 'items' => 'Edamame and cherry tomatoes'],
            ['name' => 'Lunch', 'items' => 'Lentil soup, whole grain bread'],
            ['name' => 'Snack', 'items' => 'Protein bar'],
            ['name' => 'Dinner', 'items' => 'Tofu stir-fry with brown rice'],
        ]
    ],
    [
        'title' => 'Keto Meal Plan',
        'description' => 'A low-carb, high-fat plan for those following a ketogenic diet.',
        'calories' => 1800,
        'protein' => '100g',
        'carbs' => '40g',
        'fat' => '140g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Scrambled eggs with spinach and avocado'],
            ['name' => 'Snack', 'items' => 'Cheese sticks and walnuts'],
            ['name' => 'Lunch', 'items' => 'Grilled salmon, zucchini noodles, olive oil'],
            ['name' => 'Snack', 'items' => 'Celery with almond butter'],
            ['name' => 'Dinner', 'items' => 'Chicken thighs, cauliflower mash, green beans'],
        ]
    ],
    [
        'title' => 'High-Energy Athlete Meal Plan',
        'description' => 'A high-calorie, high-carb plan for endurance athletes.',
        'calories' => 3500,
        'protein' => '180g',
        'carbs' => '500g',
        'fat' => '80g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Pancakes with honey, scrambled eggs, orange juice'],
            ['name' => 'Snack', 'items' => 'Granola bar and banana'],
            ['name' => 'Lunch', 'items' => 'Turkey wrap, pasta salad, grapes'],
            ['name' => 'Snack', 'items' => 'Trail mix and yogurt'],
            ['name' => 'Dinner', 'items' => 'Grilled fish, brown rice, roasted vegetables'],
        ]
    ],
    [
        'title' => 'Vegan Muscle Gain Meal Plan',
        'description' => 'A plant-based, high-protein plan for vegan muscle building.',
        'calories' => 2500,
        'protein' => '140g',
        'carbs' => '320g',
        'fat' => '70g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Tofu scramble, whole grain toast, berries'],
            ['name' => 'Snack', 'items' => 'Vegan protein shake and almonds'],
            ['name' => 'Lunch', 'items' => 'Chickpea salad sandwich, carrot sticks'],
            ['name' => 'Snack', 'items' => 'Roasted edamame'],
            ['name' => 'Dinner', 'items' => 'Lentil curry, brown rice, steamed broccoli'],
        ]
    ],
    [
        'title' => 'Gluten-Free Fitness Meal Plan',
        'description' => 'A balanced plan for those with gluten intolerance or celiac disease.',
        'calories' => 2000,
        'protein' => '120g',
        'carbs' => '210g',
        'fat' => '60g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Gluten-free oats with banana and chia seeds'],
            ['name' => 'Snack', 'items' => 'Rice cakes with almond butter'],
            ['name' => 'Lunch', 'items' => 'Grilled chicken, quinoa, mixed greens'],
            ['name' => 'Snack', 'items' => 'Greek yogurt and blueberries'],
            ['name' => 'Dinner', 'items' => 'Baked cod, sweet potato, green beans'],
        ]
    ],
    [
        'title' => 'Mediterranean Diet Plan',
        'description' => 'A heart-healthy plan rich in fruits, vegetables, and healthy fats.',
        'calories' => 2100,
        'protein' => '100g',
        'carbs' => '230g',
        'fat' => '80g',
        'meals' => [
            ['name' => 'Breakfast', 'items' => 'Greek yogurt with honey and walnuts'],
            ['name' => 'Snack', 'items' => 'Fresh figs and almonds'],
            ['name' => 'Lunch', 'items' => 'Grilled fish, tabbouleh, tomato salad'],
            ['name' => 'Snack', 'items' => 'Hummus with cucumber slices'],
            ['name' => 'Dinner', 'items' => 'Chicken souvlaki, brown rice, roasted vegetables'],
        ]
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietary Meal Plans - FitTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <style>
        .mealplan-list { max-width: 900px; margin: 0 auto; }
        .mealplan-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; margin-bottom: 2em; padding: 2em; }
        .mealplan-title { font-size: 1.5em; margin-bottom: 0.2em; }
        .mealplan-desc { color: #555; margin-bottom: 1em; }
        .nutrients { margin-bottom: 1em; }
        .nutrients span { display: inline-block; margin-right: 1.5em; font-weight: bold; }
        .meals { margin-top: 1em; }
        .meals th, .meals td { padding: 0.3em 0.7em; }
        .meals th { background: #f7fafc; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="mealplan-container">
        <h1>Dietary Meal Plans</h1>
        <div class="mealplan-list">
            <?php foreach ($mealPlans as $plan): ?>
                <div class="mealplan-card">
                    <div class="mealplan-title"><?php echo htmlspecialchars($plan['title']); ?></div>
                    <div class="mealplan-desc"><?php echo htmlspecialchars($plan['description']); ?></div>
                    <div class="nutrients">
                        <span>Calories: <?php echo htmlspecialchars($plan['calories']); ?></span>
                        <span>Protein: <?php echo htmlspecialchars($plan['protein']); ?></span>
                        <span>Carbs: <?php echo htmlspecialchars($plan['carbs']); ?></span>
                        <span>Fat: <?php echo htmlspecialchars($plan['fat']); ?></span>
                    </div>
                    <table class="meals">
                        <thead>
                            <tr><th>Meal</th><th>Example Foods</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($plan['meals'] as $meal): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($meal['name']); ?></td>
                                    <td><?php echo htmlspecialchars($meal['items']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
