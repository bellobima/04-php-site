<?php
$pageTitle = 'Home';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-content">
        <p class="eyebrow">Independent creative studio</p>
        <h1>Make your next idea <span>impossible to ignore.</span></h1>
        <p class="hero-description">
            BIMALEGENDFX creates bold visuals, thoughtful branding, and motion that brings your story to life.
        </p>
        <div class="button-row">
            <a class="button button-primary" href="services.php">Explore our services</a>
            <a class="button button-quiet" href="contact.php">Start a project <span aria-hidden="true">&rarr;</span></a>
        </div>
        <div class="hero-note">
            <span class="status-dot" aria-hidden="true"></span>
            <span>Good ideas deserve a great finish.</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container intro-grid">
        <div>
            <p class="eyebrow">Design with a point of view</p>
            <h2>Creative work built to move people.</h2>
        </div>
        <div>
            <p class="section-copy">
                From the first rough sketch to the final frame, we help ambitious people turn a spark into something
                memorable. Clear thinking, expressive design, and careful craft are at the heart of every project.
            </p>
            <a class="text-link" href="about.php">Get to know the studio <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>
</section>

<section class="section section-muted">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="eyebrow">What we do</p>
                <h2>One studio. Many ways to stand out.</h2>
            </div>
            <a class="text-link" href="services.php">All services <span aria-hidden="true">&rarr;</span></a>
        </div>
        <div class="card-grid">
            <article class="service-card">
                <span class="card-number">01</span>
                <h3>Visual effects</h3>
                <p>Polished compositing and imaginative details that make every frame feel intentional.</p>
            </article>
            <article class="service-card">
                <span class="card-number">02</span>
                <h3>Motion &amp; video</h3>
                <p>Rhythmic edits and expressive animation designed to keep your story moving.</p>
            </article>
            <article class="service-card">
                <span class="card-number">03</span>
                <h3>Brand identity</h3>
                <p>Distinctive visual systems that help your brand show up with confidence.</p>
            </article>
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="container cta-content">
        <div>
            <p class="eyebrow">Have something in mind?</p>
            <h2>Let's make it real.</h2>
        </div>
        <a class="button button-light" href="contact.php">Tell us about it <span aria-hidden="true">&rarr;</span></a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
