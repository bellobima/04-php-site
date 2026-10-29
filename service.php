<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

$slug = isset($_GET['slug']) && is_string($_GET['slug']) ? $_GET['slug'] : '';
$service = null;

foreach ($services as $serviceItem) {
    if ($serviceItem['slug'] === $slug) {
        $service = $serviceItem;
        break;
    }
}

if ($service === null) {
    http_response_code(404);
    $pageTitle = 'Service not found';
} else {
    $pageTitle = $service['title'];
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <?php if ($service === null): ?>
            <p class="eyebrow">A little detour</p>
            <h1>We couldn't find <span>that service.</span></h1>
            <p class="hero-description">
                The service may have moved or the link may be incorrect. Browse our services to find what you need.
            </p>
            <a class="button button-primary" href="services.php">Explore our services</a>
        <?php else: ?>
            <p class="eyebrow"><?= e($service['icon']) ?> &nbsp; Our services</p>
            <h1><?= e($service['title']) ?></h1>
            <p class="hero-description"><?= e($service['description']) ?></p>
            <p class="service-detail-price">Starting at <?= e(formatPrice($service['price'])) ?></p>
            <a class="button button-primary" href="contact.php">Start a project</a>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
