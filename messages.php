<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Messages';
$messages = [];
$errors = [];
$isMessagesPasswordConfigured = is_string($messagesPagePassword) && $messagesPagePassword !== '';
$isAuthenticated = $isMessagesPasswordConfigured
    && ($_SESSION['messages_authenticated'] ?? false) === true;

if (!$isMessagesPasswordConfigured) {
    $errors[] = 'The messages page password is not configured. Set BIMALEGENDFX_MESSAGES_PASSWORD in the server environment.';
}

if (!isset($_SESSION['messages_csrf_token']) || !is_string($_SESSION['messages_csrf_token'])) {
    $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($postedToken) || !hash_equals($_SESSION['messages_csrf_token'], $postedToken)) {
        $errors[] = 'Your session has expired. Reload the page and try again.';
    } elseif (isset($_POST['logout'])) {
        unset($_SESSION['messages_authenticated']);
        session_regenerate_id(true);
        header('Location: messages.php', true, 303);
        exit;
    } elseif (isset($_POST['password'])) {
        $password = $_POST['password'];
        if ($isMessagesPasswordConfigured && is_string($password) && hash_equals($messagesPagePassword, $password)) {
            session_regenerate_id(true);
            $_SESSION['messages_authenticated'] = true;
            $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
            header('Location: messages.php', true, 303);
            exit;
        }

        if ($isMessagesPasswordConfigured) {
            $errors[] = 'The password was incorrect. Please try again.';
        }
    }

    $isAuthenticated = $isMessagesPasswordConfigured
        && ($_SESSION['messages_authenticated'] ?? false) === true;
}

if ($isAuthenticated) {
    $messagesPath = __DIR__ . '/storage/messages.jsonl';

    if (is_file($messagesPath)) {
        if (!is_readable($messagesPath)) {
            $errors[] = 'Messages could not be read. Check the storage file permissions.';
        } else {
            $contents = file_get_contents($messagesPath);
            if ($contents === false) {
                $errors[] = 'Messages could not be read. Please check the storage file permissions.';
            } else {
                $malformedLines = 0;
                $lines = preg_split('/\r\n|\n|\r/', $contents);

                if ($lines === false) {
                    $errors[] = 'The messages file could not be processed.';
                } else {
                    foreach ($lines as $position => $line) {
                        if (trim($line) === '') {
                            continue;
                        }

                        try {
                            $record = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
                        } catch (JsonException) {
                            $malformedLines++;
                            continue;
                        }

                        if (!is_array($record)) {
                            $malformedLines++;
                            continue;
                        }

                        $timestamp = is_string($record['timestamp'] ?? null) ? $record['timestamp'] : '';
                        $parsedTimestamp = $timestamp !== '' ? strtotime($timestamp) : false;
                        $messages[] = [
                            'timestamp' => $timestamp,
                            'sort_timestamp' => $parsedTimestamp === false ? 0 : $parsedTimestamp,
                            'position' => $position,
                            'name' => is_string($record['name'] ?? null) ? $record['name'] : '',
                            'email' => is_string($record['email'] ?? null) ? $record['email'] : '',
                            'phone' => is_string($record['phone'] ?? null) ? $record['phone'] : '',
                            'service' => is_string($record['service'] ?? null) ? $record['service'] : '',
                            'message' => is_string($record['message'] ?? null) ? $record['message'] : '',
                        ];
                    }

                    if ($malformedLines > 0) {
                        $errors[] = $malformedLines . ' malformed message line(s) were skipped.';
                    }
                }
            }
        }
    }

    usort(
        $messages,
        static fn (array $first, array $second): int =>
            ($second['sort_timestamp'] <=> $first['sort_timestamp'])
            ?: ($second['position'] <=> $first['position'])
    );
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <p class="eyebrow">Studio inbox</p>
        <h1>Project <span>messages.</span></h1>
        <p class="hero-description">Review the messages submitted through the BIMALEGENDFX contact form.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php foreach ($errors as $error): ?>
            <p class="form-alert form-alert-error" role="alert"><?= e($error) ?></p>
        <?php endforeach; ?>

        <?php if (!$isAuthenticated): ?>
            <div class="messages-login-card">
                <h2>Sign in to view messages</h2>
                <form action="messages.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['messages_csrf_token']) ?>">
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <button class="button button-primary" type="submit">View messages</button>
                </form>
            </div>
        <?php else: ?>
            <div class="messages-heading">
                <h2>Submissions <span class="count-badge"><?= count($messages) ?></span></h2>
                <form action="messages.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['messages_csrf_token']) ?>">
                    <button class="button button-quiet" type="submit" name="logout" value="1">Sign out</button>
                </form>
            </div>

            <?php if ($messages === []): ?>
                <p class="messages-empty">There are no messages yet.</p>
            <?php else: ?>
                <div class="messages-table-wrap">
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th scope="col">Received</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Service</th>
                                <th scope="col">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $message): ?>
                                <?php
                                $receivedAt = $message['sort_timestamp'] > 0
                                    ? date('M j, Y g:i a', $message['sort_timestamp'])
                                    : $message['timestamp'];
                                ?>
                                <tr>
                                    <td><?= e($receivedAt) ?></td>
                                    <td><?= e($message['name']) ?></td>
                                    <td><?= e($message['email']) ?></td>
                                    <td><?= e($message['phone']) ?></td>
                                    <td><?= e($message['service']) ?></td>
                                    <td class="message-cell"><?= nl2br(e($message['message'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
