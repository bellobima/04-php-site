<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Contact';
$fields = ['name', 'email', 'phone', 'service', 'message'];
$values = array_fill_keys($fields, '');
$errors = [];

if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $field) {
        $postedValue = $_POST[$field] ?? '';
        $values[$field] = is_string($postedValue) ? trim($postedValue) : '';
    }

    $postedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($postedToken) || !hash_equals($_SESSION['csrf_token'], $postedToken)) {
        $errors['_csrf'] = 'Your form session has expired. Please review and submit the form again.';
    }

    if ($values['name'] === '') {
        $errors['name'] = 'Please enter your name.';
    }

    if ($values['email'] === '') {
        $errors['email'] = 'Please enter your email address.';
    } elseif (filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($values['service'] === '') {
        $errors['service'] = 'Please choose a service.';
    } else {
        $serviceExists = false;
        foreach ($services as $service) {
            if ($service['slug'] === $values['service']) {
                $serviceExists = true;
                break;
            }
        }

        if (!$serviceExists) {
            $errors['service'] = 'Please choose one of the listed services.';
        }
    }

    $messageCharacterCount = preg_match_all('/./us', $values['message'], $messageCharacters);
    if ($messageCharacterCount === false || $messageCharacterCount < 10 || $messageCharacterCount > 1000) {
        $errors['message'] = 'Your message must be between 10 and 1,000 characters.';
    }

    if ($errors === []) {
        $storageDirectory = __DIR__ . '/storage';
        if (!is_dir($storageDirectory) && !mkdir($storageDirectory, 0750, true) && !is_dir($storageDirectory)) {
            $errors['_form'] = 'We could not save your message right now. Please try again later.';
        } else {
            try {
                $submission = [
                    'timestamp' => date(DATE_ATOM),
                    'name' => $values['name'],
                    'email' => $values['email'],
                    'phone' => $values['phone'],
                    'service' => $values['service'],
                    'message' => $values['message'],
                ];
                $jsonLine = json_encode($submission, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . PHP_EOL;
            } catch (JsonException $exception) {
                error_log('Unable to encode contact form submission: ' . $exception->getMessage());
                $errors['_form'] = 'We could not save your message right now. Please try again later.';
            }

            if (!isset($errors['_form'])) {
                $bytesWritten = file_put_contents(
                    $storageDirectory . '/messages.jsonl',
                    $jsonLine,
                    FILE_APPEND | LOCK_EX
                );

                if ($bytesWritten === false) {
                    $errors['_form'] = 'We could not save your message right now. Please try again later.';
                } else {
                    header('Location: contact.php?sent=1', true, 303);
                    exit;
                }
            }
        }
    }
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <p class="eyebrow">Your next project starts here</p>
        <h1>Tell us what <span>you're dreaming up.</span></h1>
        <p class="hero-description">
            A few details are enough to get the conversation going. We'd love to hear what you have in mind.
        </p>
    </div>
</section>

<section class="section">
    <div class="container contact-grid">
        <div>
            <p class="eyebrow">Get in touch</p>
            <h2>Let's make something memorable.</h2>
            <p class="section-copy">
                Share a little about your project, where you are in the process, and what a great result would look like.
                We'll take it from there.
            </p>
            <div class="contact-card">
                <span class="contact-icon" aria-hidden="true">@</span>
                <p class="eyebrow">Email the studio</p>
                <a class="contact-email" href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>
                <p>Tell us about your idea and we'll be in touch.</p>
            </div>
        </div>

        <div class="contact-form-card">
            <h2>Tell us about your project</h2>

            <?php if (isset($_GET['sent']) && $_GET['sent'] === '1'): ?>
                <p class="form-alert form-alert-success" role="status">Thanks for reaching out. Your message has been received.</p>
            <?php endif; ?>

            <?php if (isset($errors['_form'])): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($errors['_form']) ?></p>
            <?php endif; ?>

            <?php if (isset($errors['_csrf'])): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($errors['_csrf']) ?></p>
            <?php endif; ?>

            <form action="contact.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="form-field">
                    <label for="name">Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        autocomplete="name"
                        value="<?= e($values['name']) ?>"
                        required
                        <?= isset($errors['name']) ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?>
                    >
                    <?php if (isset($errors['name'])): ?>
                        <p class="field-error" id="name-error"><?= e($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        value="<?= e($values['email']) ?>"
                        required
                        <?= isset($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>
                    >
                    <?php if (isset($errors['email'])): ?>
                        <p class="field-error" id="email-error"><?= e($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="phone">Phone <span class="optional-label">(optional)</span></label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        autocomplete="tel"
                        value="<?= e($values['phone']) ?>"
                    >
                </div>

                <div class="form-field">
                    <label for="service">Service</label>
                    <select
                        id="service"
                        name="service"
                        required
                        <?= isset($errors['service']) ? 'aria-invalid="true" aria-describedby="service-error"' : '' ?>
                    >
                        <option value="">Choose a service</option>
                        <?php foreach ($services as $service): ?>
                            <option
                                value="<?= e($service['slug']) ?>"
                                <?= $values['service'] === $service['slug'] ? 'selected' : '' ?>
                            ><?= e($service['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['service'])): ?>
                        <p class="field-error" id="service-error"><?= e($errors['service']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="message">Message</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="6"
                        required
                        aria-describedby="<?= isset($errors['message']) ? 'message-error' : 'message-hint' ?>"
                        <?= isset($errors['message']) ? 'aria-invalid="true"' : '' ?>
                    ><?= e($values['message']) ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                        <p class="field-error" id="message-error"><?= e($errors['message']) ?></p>
                    <?php else: ?>
                        <p class="field-hint" id="message-hint">Please use 10 to 1,000 characters.</p>
                    <?php endif; ?>
                </div>

                <button class="button button-primary" type="submit">Send message</button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
