<?php
declare(strict_types=1);

$pageTitle = 'Services';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <p class="eyebrow">What we bring to the table</p>
        <h1>Ideas, meet <span>execution.</span></h1>
        <p class="hero-description">
            Flexible creative services for stories, products, and brands ready for their close-up.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card-grid">
            <?php foreach ($services as $service): ?>
                <?= renderServiceCard($service) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="container cta-content">
        <div>
            <p class="eyebrow">Not sure where to start?</p>
            <h2>Bring us the rough idea.</h2>
        </div>
        <a class="button button-light" href="contact.php">Let's talk <span aria-hidden="true">&rarr;</span></a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
