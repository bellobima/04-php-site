<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $amount): string
{
    return '₦' . number_format($amount, 2);
}

function renderServiceCard(array $service): string
{
    $slug = e(rawurlencode($service['slug']));
    $title = e($service['title']);
    $description = e($service['description']);
    $icon = e($service['icon']);
    $price = e(formatPrice($service['price']));

    return <<<HTML
        <article class="service-card">
            <span class="service-card-icon" aria-hidden="true">{$icon}</span>
            <h3>{$title}</h3>
            <p>{$description}</p>
            <p class="service-card-price">From {$price}</p>
            <a class="text-link" href="service.php?slug={$slug}" aria-label="View details for {$title}">
                View service <span aria-hidden="true">&rarr;</span>
            </a>
        </article>
        HTML;
}
