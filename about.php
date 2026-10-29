<?php
declare(strict_types=1);

$pageTitle = 'About';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <p class="eyebrow">A little about us</p>
        <h1>Small studio.<br><span>Big-picture thinking.</span></h1>
        <p class="hero-description">
            We are BIMALEGENDFX, an independent creative studio for brands and stories that want to leave a mark.
        </p>
    </div>
</section>

<section class="section">
    <div class="container content-grid">
        <div>
            <p class="eyebrow">Our approach</p>
            <h2>Good work starts with good questions.</h2>
        </div>
        <div class="prose">
            <p>
                Every project begins by listening: what are you trying to say, who needs to hear it, and how should
                it make them feel? We bring those answers into the creative process so the final work looks great
                and has a reason to exist.
            </p>
            <p>
                As a fictional independent studio, BIMALEGENDFX brings together visual effects, motion design,
                video editing, and brand identity under one roof. The result is a friendly, focused creative
                partnership from first conversation to final delivery.
            </p>
            <a class="text-link" href="contact.php">Let's talk about your project <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>
</section>

<section class="section section-muted">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Our people</p>
                <h2>Meet the team.</h2>
            </div>
        </div>
        <div class="values-grid">
            <?php foreach ($team as $member): ?>
                <article class="value-item">
                    <h3><?= e($member['name']) ?></h3>
                    <p class="team-role"><?= e($member['role']) ?></p>
                    <p><?= e($member['bio']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
